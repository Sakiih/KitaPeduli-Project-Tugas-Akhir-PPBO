<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\FundDisbursementReport;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardApiController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::where('status', Campaign::STATUS_ACTIVE)->count();
        $totalTransactions = Donation::count();
        $successfulDonationsCount = Donation::where('status', Donation::STATUS_SUCCESS)->count();
        $pendingDonationsCount = Donation::where('status', Donation::STATUS_PENDING)->count();
        $totalCollectedFunds = (float) Donation::where('status', Donation::STATUS_SUCCESS)->sum('amount');
        $registeredDonorsCount = User::where('role', User::ROLE_DONOR)->count();
        $guestTransactionsCount = Donation::whereNull('user_id')->count();
        $guestSuccessCount = Donation::whereNull('user_id')->where('status', Donation::STATUS_SUCCESS)->count();
        $totalDisbursedFunds = (float) FundDisbursementReport::sum('amount');

        $recentTransactions = Donation::with(['campaign:id,title,slug', 'payment'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Statistik dashboard Admin berhasil diambil.',
            'data' => [
                'statistics' => [
                    'total_campaigns' => $totalCampaigns,
                    'active_campaigns' => $activeCampaigns,
                    'total_categories' => Category::count(),
                    'total_transactions' => $totalTransactions,
                    'successful_donations_count' => $successfulDonationsCount,
                    'pending_donations_count' => $pendingDonationsCount,
                    'total_collected_funds' => $totalCollectedFunds,
                    'total_disbursed_funds' => $totalDisbursedFunds,
                    'registered_donors_count' => $registeredDonorsCount,
                    'guest_transactions_count' => $guestTransactionsCount,
                    'guest_successful_donations_count' => $guestSuccessCount,
                ],
                'recent_transactions' => $recentTransactions,
            ],
        ]);
    }

    public function donationReport(Request $request): JsonResponse
    {
        $endDateRules = ['nullable', 'date'];
        if ($request->filled('start_date')) {
            $endDateRules[] = 'after_or_equal:start_date';
        }

        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => $endDateRules,
        ], [
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.date' => 'Format tanggal akhir tidak valid.',
            'end_date.after_or_equal' => 'Tanggal akhir harus sama dengan atau setelah tanggal mulai.',
        ]);

        $query = Donation::with(['campaign:id,title,slug', 'payment', 'user:id,name,email'])
            ->where('status', Donation::STATUS_SUCCESS);

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', (int) $request->query('campaign_id'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', (string) $request->query('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', (string) $request->query('end_date'));
        }

        if ($request->filled('payment_method')) {
            $method = (string) $request->query('payment_method');
            $query->whereHas('payment', fn ($q) => $q->where('payment_method', $method));
        }

        $totalAmount = (float) (clone $query)->sum('amount');
        $totalCount = (clone $query)->count();
        $guestCount = (clone $query)->whereNull('user_id')->count();
        $registeredCount = (clone $query)->whereNotNull('user_id')->count();

        $filteredDonationIds = (clone $query)->select('id');

        $qrisAmount = (float) Payment::where('status', Payment::STATUS_PAID)
            ->where('payment_method', Payment::METHOD_QRIS)
            ->whereIn('donation_id', $filteredDonationIds)
            ->sum('amount');

        $bankTransferAmount = (float) Payment::where('status', Payment::STATUS_PAID)
            ->where('payment_method', Payment::METHOD_BANK_TRANSFER)
            ->whereIn('donation_id', $filteredDonationIds)
            ->sum('amount');

        $perPage = min(50, max(1, (int) $request->query('per_page', 20)));
        $donations = $query->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Laporan donasi berhasil diambil.',
            'data' => [
                'summary' => [
                    'total_successful_amount' => $totalAmount,
                    'total_successful_transactions' => $totalCount,
                    'registered_donor_transactions' => $registeredCount,
                    'guest_donor_transactions' => $guestCount,
                    'by_payment_method' => [
                        'qris' => $qrisAmount,
                        'bank_transfer' => $bankTransferAmount,
                    ],
                ],
                'transactions' => $donations->getCollection(),
            ],
            'meta' => [
                'current_page' => $donations->currentPage(),
                'last_page' => $donations->lastPage(),
                'per_page' => $donations->perPage(),
                'total' => $donations->total(),
            ],
        ]);
    }
}
