<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\FundDisbursementReport;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::where('status', Campaign::STATUS_ACTIVE)->count(),
            'total_categories' => Category::count(),
            'total_transactions' => Donation::count(),
            'successful_donations' => Donation::where('status', Donation::STATUS_SUCCESS)->count(),
            'pending_donations' => Donation::where('status', Donation::STATUS_PENDING)->count(),
            'total_collected_funds' => (float) Donation::where('status', Donation::STATUS_SUCCESS)->sum('amount'),
            'total_disbursed_funds' => (float) FundDisbursementReport::sum('amount'),
            'registered_donors' => User::where('role', User::ROLE_DONOR)->count(),
            'guest_transactions' => Donation::whereNull('user_id')->count(),
        ];

        $recentDonations = Donation::with(['campaign', 'payment', 'user'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $topCampaigns = Campaign::with('category')
            ->orderByDesc('collected_amount')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentDonations', 'topCampaigns'));
    }
}
