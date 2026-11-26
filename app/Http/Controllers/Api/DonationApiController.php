<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Models\Campaign;
use App\Models\Donation;
use App\Services\DonationService;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DonationApiController extends Controller
{
    public function __construct(
        private readonly DonationService $donationService
    ) {}

    public function store(StoreDonationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $campaign = Campaign::find($validated['campaign_id']);

        if (! $campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Kampanye tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $authenticatedUser = $request->user('sanctum');
        if ($authenticatedUser !== null && ! $authenticatedUser->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sedang dinonaktifkan oleh Admin.',
                'data' => null,
            ], 403);
        }

        try {
            $result = $this->donationService->createDonation($campaign, $validated, $authenticatedUser);
        } catch (DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 422);
        }

        $donation = $result['donation'];
        $payment = $result['payment'];

        return response()->json([
            'success' => true,
            'message' => 'Transaksi donasi berhasil dibuat. Silakan lanjutkan simulasi pembayaran.',
            'data' => [
                'donation' => [
                    'id' => $donation->id,
                    'campaign_id' => $donation->campaign_id,
                    'user_id' => $donation->user_id,
                    'is_guest' => $donation->isGuest(),
                    'donor_name' => $donation->donor_name,
                    'amount' => (float) $donation->amount,
                    'donor_message' => $donation->donor_message,
                    'is_anonymous' => (bool) $donation->is_anonymous,
                    'status' => $donation->status,
                    'created_at' => $donation->created_at?->toIso8601String(),
                ],
                'payment' => [
                    'reference' => $payment->reference,
                    'payment_method' => $payment->payment_method,
                    'amount' => (float) $payment->amount,
                    'status' => $payment->status,
                    'instruction' => $result['instruction'],
                ],
                'transaction_access' => [
                    'reference' => $payment->reference,
                    'access_token' => $result['access_token'],
                    'header_name' => 'X-Transaction-Token',
                    'note' => 'Simpan token ini untuk mengakses status pembayaran dan menjalankan simulasi pembayaran.',
                ],
            ],
        ], 201);
    }

    public function myDonations(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = min(50, max(1, (int) $request->query('per_page', 10)));

        $donations = Donation::with(['campaign:id,title,slug,banner_image', 'payment'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat donasi pribadi berhasil diambil.',
            'data' => $donations->getCollection(),
            'meta' => [
                'current_page' => $donations->currentPage(),
                'last_page' => $donations->lastPage(),
                'per_page' => $donations->perPage(),
                'total' => $donations->total(),
            ],
        ]);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $donation = ctype_digit($id) ? Donation::with(['campaign', 'payment'])->find((int) $id) : null;

        if (! $donation) {
            return response()->json([
                'success' => false,
                'message' => 'Data donasi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        if (! $user->isAdmin() && ((int) $donation->user_id !== (int) $user->id || $donation->user_id === null)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak berhak mengakses transaksi milik pengguna lain.',
                'data' => null,
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail donasi berhasil diambil.',
            'data' => $donation,
        ]);
    }

    public function campaignPublicDonations(Request $request, string $campaignId): JsonResponse
    {
        $campaign = ctype_digit($campaignId) ? Campaign::find((int) $campaignId) : null;
        if (! $campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Kampanye tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $currentUserId = $request->user('sanctum')?->id;
        $perPage = min(50, max(1, (int) $request->query('per_page', 15)));

        $donations = Donation::with('reactions')
            ->where('campaign_id', $campaign->id)
            ->where('status', Donation::STATUS_SUCCESS)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar Doa-doa Orang Baik berhasil diambil.',
            'data' => $donations->getCollection()->map(fn(Donation $d) => $d->toPublicPrayerArray($currentUserId)),
            'meta' => [
                'current_page' => $donations->currentPage(),
                'last_page' => $donations->lastPage(),
                'per_page' => $donations->perPage(),
                'total' => $donations->total(),
            ],
        ]);
    }

    public function adminIndex(Request $request): JsonResponse
    {
        $query = Donation::with(['campaign:id,title,slug', 'user:id,name,email', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', (string) $request->query('status'));
        }

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', (int) $request->query('campaign_id'));
        }

        if ($request->filled('donor_type')) {
            if ($request->query('donor_type') === 'guest') {
                $query->whereNull('user_id');
            } elseif ($request->query('donor_type') === 'registered') {
                $query->whereNotNull('user_id');
            }
        }

        $perPage = min(50, max(1, (int) $request->query('per_page', 20)));
        $donations = $query->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar seluruh transaksi donasi berhasil diambil.',
            'data' => $donations->getCollection(),
            'meta' => [
                'current_page' => $donations->currentPage(),
                'last_page' => $donations->lastPage(),
                'per_page' => $donations->perPage(),
                'total' => $donations->total(),
            ],
        ]);
    }
}
