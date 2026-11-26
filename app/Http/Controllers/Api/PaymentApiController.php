<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentApiController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    public function show(Request $request, string $reference): JsonResponse
    {
        $payment = Payment::with(['donation.campaign:id,title,slug'])->where('reference', $reference)->first();

        if (! $payment) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi pembayaran tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $authenticatedUser = $request->user('sanctum');
        $transactionToken = $this->extractTransactionToken($request);

        if (! $this->paymentService->canAccessPayment($payment, $authenticatedUser, $transactionToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak memiliki akses atau token transaksi tidak valid.',
                'data' => null,
            ], 403);
        }

        $instruction = $this->paymentService->getPaymentInstruction($payment);

        return response()->json([
            'success' => true,
            'message' => 'Detail instruksi dan status pembayaran berhasil diambil.',
            'data' => [
                'reference' => $payment->reference,
                'payment_method' => $payment->payment_method,
                'amount' => (float) $payment->amount,
                'status' => $payment->status,
                'paid_at' => $payment->paid_at?->toIso8601String(),
                'donation' => [
                    'id' => $payment->donation->id,
                    'campaign_id' => $payment->donation->campaign_id,
                    'campaign_title' => $payment->donation->campaign?->title,
                    'donor_name' => $payment->donation->donor_name,
                    'is_guest' => $payment->donation->isGuest(),
                    'is_anonymous' => (bool) $payment->donation->is_anonymous,
                    'status' => $payment->donation->status,
                ],
                'instruction' => $instruction,
            ],
        ]);
    }

    public function simulateSuccess(Request $request, string $reference): JsonResponse
    {
        $payment = Payment::with('donation.campaign')->where('reference', $reference)->first();

        if (! $payment) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi pembayaran tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $authenticatedUser = $request->user('sanctum');
        $transactionToken = $this->extractTransactionToken($request);

        if (! $this->paymentService->canSimulatePayment($payment, $authenticatedUser, $transactionToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak berhak menyimulasikan pembayaran transaksi ini.',
                'data' => null,
            ], 403);
        }

        try {
            $updatedPayment = $this->paymentService->simulateSuccess($payment);
        } catch (DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Simulasi pembayaran berhasil dikonfirmasi. Status pembayaran menjadi paid dan donasi menjadi success.',
            'data' => [
                'reference' => $updatedPayment->reference,
                'payment_method' => $updatedPayment->payment_method,
                'amount' => (float) $updatedPayment->amount,
                'payment_status' => $updatedPayment->status,
                'paid_at' => $updatedPayment->paid_at?->toIso8601String(),
                'donation_status' => $updatedPayment->donation->status,
                'campaign' => [
                    'id' => $updatedPayment->donation->campaign->id,
                    'title' => $updatedPayment->donation->campaign->title,
                    'collected_amount' => (float) $updatedPayment->donation->campaign->collected_amount,
                    'donor_count' => (int) $updatedPayment->donation->campaign->donor_count,
                ],
            ],
        ]);
    }

    public function adminIndex(Request $request): JsonResponse
    {
        $query = Payment::with(['donation.campaign:id,title,slug', 'donation.user:id,name,email']);

        if ($request->filled('status')) {
            $query->where('status', (string) $request->query('status'));
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', (string) $request->query('payment_method'));
        }

        $perPage = min(50, max(1, (int) $request->query('per_page', 20)));
        $payments = $query->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar seluruh transaksi pembayaran berhasil diambil.',
            'data' => $payments->getCollection(),
            'meta' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
            ],
        ]);
    }

    // Token dibaca dari header atau body POST saja agar tidak terekspos di query string URL.
    private function extractTransactionToken(Request $request): ?string
    {
        return $request->header('X-Transaction-Token')
            ?? $request->header('X-Access-Token')
            ?? $request->post('access_token');
    }
}
