<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDonationController extends Controller
{
    public function index(Request $request): View
    {
        $campaigns = Campaign::orderBy('title')->get();
        $query = Donation::with(['campaign', 'payment', 'user']);

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

        $donations = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.donations.index', compact('donations', 'campaigns'));
    }
}
