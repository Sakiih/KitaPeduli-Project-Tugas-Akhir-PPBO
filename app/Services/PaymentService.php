<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentResolverInterface;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\User;
use DomainException;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        private readonly PaymentResolverInterface $paymentResolver
    ) {}

    public function canAccessPayment(Payment $payment, ?User $user = null, ?string $plainToken = null): bool
    {
        $payment->loadMissing('donation');
        $donation = $payment->donation;

        if (! $donation) {
            return false;
        }

        if ($user !== null && $user->isAdmin()) {
            return true;
        }

        if ($donation->user_id !== null) {
            if ($user !== null && $user->isActive() && (int) $user->id === (int) $donation->user_id) {
                return true;
            }

            return $payment->verifyAccessToken($plainToken);
        }

        return $payment->verifyAccessToken($plainToken);
    }

    public function canSimulatePayment(Payment $payment, ?User $user = null, ?string $plainToken = null): bool
    {
        $payment->loadMissing('donation');
        $donation = $payment->donation;

        if (! $donation) {
            return false;
        }

        // Admin hanya memantau transaksi dan tidak boleh menyimulasikan pembayaran donatur atau Guest.
        if ($user !== null && ($user->isAdmin() || ! $user->isActive())) {
            return false;
        }

        if ($donation->user_id !== null) {
            if ($user !== null && (int) $user->id === (int) $donation->user_id) {
                return true;
            }

            return $payment->verifyAccessToken($plainToken);
        }

        return $payment->verifyAccessToken($plainToken);
    }

    /** @return array<string, mixed> */
    public function getPaymentInstruction(Payment $payment): array
    {
        $payment->loadMissing('donation.campaign');

        $gateway = $this->paymentResolver->resolve($payment->payment_method);

        return $gateway->createInstruction($payment, $payment->donation);
    }

    public function simulateSuccess(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment): Payment {
            $lockedPayment = Payment::where('id', $payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedDonation = Donation::where('id', $lockedPayment->donation_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! $lockedPayment->isPending() || ! $lockedDonation->isPending()) {
                throw new DomainException('Transaksi pembayaran ini sudah dikonfirmasi sebelumnya dan tidak dapat diproses ulang.');
            }

            if ((float) $lockedPayment->amount <= 0 || (float) $lockedDonation->amount <= 0) {
                throw new DomainException('Nominal transaksi tidak valid untuk dikonfirmasi.');
            }

            $lockedPayment->markAsPaid();
            $lockedDonation->markAsSuccessful();

            $campaign = Campaign::where('id', $lockedDonation->campaign_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Saldo kampanye hanya diperbarui setelah status pembayaran sah menjadi paid.
            if ($lockedPayment->isPaid()) {
                $campaign->recalculateCollectedAmount();
            }

            return $lockedPayment->fresh(['donation.campaign']);
        });
    }
}
