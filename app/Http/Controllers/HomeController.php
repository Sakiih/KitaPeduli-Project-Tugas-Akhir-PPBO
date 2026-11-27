<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::withCount(['campaigns' => function ($q) {
            $q->where('status', Campaign::STATUS_ACTIVE);
        }])->orderBy('name')->get();

        $totalActiveCampaigns = Campaign::where('status', Campaign::STATUS_ACTIVE)->count();

        $featuredCampaign = Campaign::with('category')
            ->where('status', Campaign::STATUS_ACTIVE)
            ->orderByDesc('is_featured')
            ->orderByDesc('collected_amount')
            ->first();

        $query = Campaign::with('category')
            ->where('status', Campaign::STATUS_ACTIVE);

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('organizer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $catSlug = (string) $request->query('category');
            $query->whereHas('category', fn ($q) => $q->where('slug', $catSlug));
        } elseif ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->query('category_id'));
        }

        $sort = (string) $request->query('sort', 'latest');
        match ($sort) {
            'ending_soon' => $query->orderBy('end_date', 'asc'),
            'most_funded' => $query->orderByDesc('collected_amount'),
            'featured' => $query->orderByDesc('is_featured')->orderByDesc('created_at'),
            default => $query->orderByDesc('created_at')->orderByDesc('id'),
        };

        $campaigns = $query->paginate(6)->withQueryString();

        $recommendations = Campaign::with('category')
            ->where('status', Campaign::STATUS_ACTIVE)
            ->orderByDesc('is_featured')
            ->orderBy('end_date', 'asc')
            ->limit(4)
            ->get();

        $platformStats = [
            'active_campaigns' => $totalActiveCampaigns,
            'total_collected' => (float) Donation::where('status', Donation::STATUS_SUCCESS)->sum('amount'),
            'total_donors' => Donation::where('status', Donation::STATUS_SUCCESS)->count(),
        ];

        return view('home.index', compact(
            'categories',
            'totalActiveCampaigns',
            'featuredCampaign',
            'campaigns',
            'recommendations',
            'platformStats',
            'sort'
        ));
    }

    public function about(): View
    {
        return view('home.about');
    }
}
