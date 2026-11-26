<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\PaymentResolverInterface;
use App\Http\Requests\StoreDonationRequest;
use App\Models\Campaign;
use App\Models\Donation;
use App\Services\DonationService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function __construct(
        private readonly DonationService $donationService,
        private readonly PaymentResolverInterface $paymentResolver
    ) {}

    public function create(Request $request, string $slug): View|RedirectResponse
    {
        $campaignQuery = Campaign::with('category');
        $campaign = ctype_digit($slug)
            ? $campaignQuery->where('id', (int) $slug)->firstOrFail()
            : $campaignQuery->where('slug', $slug)->firstOrFail();

        if (! $campaign->isAcceptingDonations()) {
            return redirect()->route('campaigns.show', $campaign->slug)
                ->with('error', 'Kampanye ini sudah berakhir atau sedang tidak aktif sehingga tidak dapat menerima donasi baru.');
        }

        if ($request->user() && $request->user()->isAdmin()) {
            return redirect()->route('campaigns.show', $campaign->slug)
                ->with('error', 'Akun Admin bertugas mengelola kampanye dan tidak dapat melakukan transaksi donasi.');
        }

        $paymentMethods = $this->paymentResolver->supportedMethods();

        return view('donations.create', compact('campaign', 'paymentMethods'));
    }

    public function store(StoreDonationRequest $request, string $slug): RedirectResponse
    {
        $campaign = ctype_digit($slug)
            ? Campaign::where('id', (int) $slug)->firstOrFail()
            : Campaign::where('slug', $slug)->firstOrFail();

        try {
            $result = $this->donationService->createDonation(
                $campaign,
                $request->validated(),
                $request->user()
            );
        } catch (DomainException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        $payment = $result['payment'];

        // Simpan token akses di session agar tidak muncul pada URL query string.
        $request->session()->put("payment_tokens.{$payment->reference}", $result['access_token']);

        return redirect()->route('payments.show', $payment->reference)
            ->with('success', 'Transaksi donasi berhasil dibuat. Silakan jalankan simulasi pembayaran di bawah ini.');
    }

    public function toggleAmen(Request $request, Donation $donation): RedirectResponse
    {
        try {
            $result = $this->donationService->toggleAmen($donation, $request->user());
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', $result['message']);
    }
}
