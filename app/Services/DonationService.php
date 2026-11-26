<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentResolverInterface;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\DonationReaction;
use App\Models\Payment;
use App\Models\User;
use DomainException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationService
{
    public function __construct(
        private readonly PaymentResolverInterface $paymentResolver
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @return array{donation: Donation, payment: Payment, access_token: string, instruction: array<string, mixed>}
     */
    public function createDonation(Campaign $campaign, array $data, ?User $authenticatedUser = null): array
    {
        if ($authenticatedUser !== null && $authenticatedUser->isAdmin()) {
            throw new DomainException('Akun Admin bertugas mengelola kampanye dan tidak dapat melakukan transaksi donasi.');
        }

        if (! $campaign->isAcceptingDonations()) {
            throw new DomainException('Kampanye ini sudah berakhir atau sedang tidak aktif sehingga tidak dapat menerima donasi baru.');
        }

        $amount = (float) ($data['amount'] ?? 0);
        if ($amount <= 0) {
            throw new DomainException('Nominal donasi harus bernilai positif.');
        }

        $paymentMethod = $this->paymentResolver->resolve((string) $data['payment_method']);

        // Identitas user_id selalu ditentukan dari sesi atau token backend, bukan input client.
        $userId = $authenticatedUser?->id;

        $donorName = trim((string) ($data['donor_name'] ?? ($authenticatedUser?->name ?? '')));
        $donorEmail = $data['donor_email'] ?? $authenticatedUser?->email;
        $donorPhone = $data['donor_phone'] ?? $authenticatedUser?->phone;

        return DB::transaction(function () use ($campaign, $data, $userId, $donorName, $donorEmail, $donorPhone, $amount, $paymentMethod): array {
            $donation = Donation::create([
                'campaign_id' => $campaign->id,
                'user_id' => $userId,
                'donor_name' => $donorName,
                'donor_email' => $donorEmail ?: null,
                'donor_phone' => $donorPhone ?: null,
                'amount' => $amount,
                'donor_message' => ! empty($data['donor_message']) ? trim((string) $data['donor_message']) : null,
                'is_anonymous' => (bool) ($data['is_anonymous'] ?? false),
                'status' => Donation::STATUS_PENDING,
                'amen_count' => 0,
            ]);

            $donation->setRelation('campaign', $campaign);

            $reference = $this->generateUniqueReference();
            $plainAccessToken = Str::random(48);

            $payment = Payment::create([
                'donation_id' => $donation->id,
                'payment_method' => $paymentMethod->getCode(),
                'reference' => $reference,
                'access_token_hash' => hash('sha256', $plainAccessToken),
                'amount' => $amount,
                'status' => Payment::STATUS_PENDING,
            ]);

            $instruction = $paymentMethod->createInstruction($payment, $donation);
            $payment->instruction_payload = $instruction;
            $payment->save();

            $donation->setRelation('payment', $payment);

            return [
                'donation' => $donation,
                'payment' => $payment,
                'access_token' => $plainAccessToken,
                'instruction' => $instruction,
            ];
        });
    }

    /** @return array{created: bool, amen_count: int} */
    public function giveAmen(Donation $donation, User $user): array
    {
        if (! $user->isDonor()) {
            throw new DomainException('Hanya Donatur Terdaftar yang dapat memberikan Aamiin.');
        }

        if (! $donation->isSuccessful()) {
            throw new DomainException('Aamiin hanya dapat diberikan pada donasi yang telah berhasil.');
        }

        try {
            return DB::transaction(function () use ($donation, $user): array {
                $existing = DonationReaction::where('donation_id', $donation->id)
                    ->where('user_id', $user->id)
                    ->first();

                if ($existing) {
                    throw new DomainException('Anda sudah memberikan Aamiin pada doa donasi ini.');
                }

                DonationReaction::create([
                    'donation_id' => $donation->id,
                    'user_id' => $user->id,
                ]);

                $donation->refreshAmenCount();

                return [
                    'created' => true,
                    'amen_count' => (int) $donation->amen_count,
                ];
            });
        } catch (QueryException $e) {
            $sqlState = $e->errorInfo[0] ?? $e->getCode();
            if ($sqlState === '23000') {
                throw new DomainException('Anda sudah memberikan Aamiin pada doa donasi ini.');
            }

            throw $e;
        }
    }

    /** @return array{removed: bool, amen_count: int} */
    public function removeAmen(Donation $donation, User $user): array
    {
        if (! $user->isDonor()) {
            throw new DomainException('Hanya Donatur Terdaftar yang dapat mengelola reaksi Aamiin.');
        }

        return DB::transaction(function () use ($donation, $user): array {
            $deleted = DonationReaction::where('donation_id', $donation->id)
                ->where('user_id', $user->id)
                ->delete();

            if ($deleted === 0) {
                throw new DomainException('Anda belum memberikan Aamiin pada doa donasi ini.');
            }

            $donation->refreshAmenCount();

            return [
                'removed' => true,
                'amen_count' => (int) $donation->amen_count,
            ];
        });
    }

    /** @return array{action: string, message: string, amen_count: int} */
    public function toggleAmen(Donation $donation, User $user): array
    {
        if (! $user->isDonor()) {
            throw new DomainException('Hanya Donatur Terdaftar yang dapat mengelola reaksi Aamiin.');
        }

        if (! $donation->isSuccessful()) {
            throw new DomainException('Aamiin hanya dapat diberikan pada donasi yang telah berhasil.');
        }

        return DB::transaction(function () use ($donation, $user): array {
            $existing = DonationReaction::where('donation_id', $donation->id)
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                $existing->delete();
                $donation->refreshAmenCount();

                return [
                    'action' => 'removed',
                    'message' => 'Reaksi Aamiin pada doa telah dibatalkan.',
                    'amen_count' => (int) $donation->amen_count,
                ];
            }

            DonationReaction::create([
                'donation_id' => $donation->id,
                'user_id' => $user->id,
            ]);
            $donation->refreshAmenCount();

            return [
                'action' => 'added',
                'message' => 'Terima kasih telah mengaminkan doa Orang Baik.',
                'amen_count' => (int) $donation->amen_count,
            ];
        });
    }

    private function generateUniqueReference(): string
    {
        do {
            $reference = 'KP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
        } while (Payment::where('reference', $reference)->exists());

        return $reference;
    }
}
