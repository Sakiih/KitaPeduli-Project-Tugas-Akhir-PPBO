<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    public function show(Request $request, string $reference): View
    {
        $payment = Payment::with(['donation.campaign.category'])
            ->where('reference', $reference)
            ->firstOrFail();

        $token = $this->resolveSessionOrHeaderToken($request, $payment->reference);

        if (! $this->paymentService->canAccessPayment($payment, $request->user(), $token)) {
            abort(403, 'Anda tidak memiliki akses ke transaksi pembayaran ini atau sesi token transaksi Guest tidak ditemukan.');
        }

        $instruction = $this->paymentService->getPaymentInstruction($payment);
        $donation = $payment->donation;
        $campaign = $donation->campaign;

        return view('payments.show', compact('payment', 'donation', 'campaign', 'instruction'));
    }

    public function simulateSuccess(Request $request, string $reference): RedirectResponse
    {
        $payment = Payment::with('donation.campaign')
            ->where('reference', $reference)
            ->firstOrFail();

        $token = $this->resolveSessionOrHeaderToken($request, $payment->reference);

        if (! $this->paymentService->canSimulatePayment($payment, $request->user(), $token)) {
            abort(403, 'Anda tidak berhak mengonfirmasi simulasi pembayaran transaksi ini.');
        }

        try {
            $this->paymentService->simulateSuccess($payment);
        } catch (DomainException $e) {
            return redirect()->route('payments.show', $payment->reference)
                ->with('error', $e->getMessage());
        }

        return redirect()->route('payments.show', $payment->reference)
            ->with('success', 'Simulasi pembayaran berhasil. Status pembayaran kini dibayar (paid) dan donasi tercatat berhasil (success).');
    }

    private function resolveSessionOrHeaderToken(Request $request, string $reference): ?string
    {
        return $request->session()->get("payment_tokens.{$reference}")
            ?? $request->header('X-Transaction-Token')
            ?? $request->post('access_token');
    }
}
