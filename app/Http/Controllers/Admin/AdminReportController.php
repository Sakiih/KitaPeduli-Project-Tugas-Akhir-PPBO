<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        $campaigns = Campaign::orderBy('title')->get();

        $query = Donation::with(['campaign', 'payment', 'user'])
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

        $filteredDonationIds = (clone $query)->select('id');

        $summary = [
            'total_amount' => (float) (clone $query)->sum('amount'),
            'total_count' => (clone $query)->count(),
            'registered_count' => (clone $query)->whereNotNull('user_id')->count(),
            'guest_count' => (clone $query)->whereNull('user_id')->count(),
            'qris_amount' => (float) Payment::where('status', Payment::STATUS_PAID)
                ->where('payment_method', Payment::METHOD_QRIS)
                ->whereIn('donation_id', $filteredDonationIds)
                ->sum('amount'),
            'bank_amount' => (float) Payment::where('status', Payment::STATUS_PAID)
                ->where('payment_method', Payment::METHOD_BANK_TRANSFER)
                ->whereIn('donation_id', $filteredDonationIds)
                ->sum('amount'),
        ];

        $donations = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('campaigns', 'summary', 'donations'));
    }
}
