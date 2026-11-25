<?php

declare(strict_types=1);

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DonorDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $totalSuccessAmount = (float) Donation::where('user_id', $user->id)
            ->where('status', Donation::STATUS_SUCCESS)
            ->sum('amount');

        $totalSuccessCount = Donation::where('user_id', $user->id)
            ->where('status', Donation::STATUS_SUCCESS)
            ->count();

        $totalPendingCount = Donation::where('user_id', $user->id)
            ->where('status', Donation::STATUS_PENDING)
            ->count();

        $totalAmenGiven = $user->reactions()->count();

        $recentDonations = Donation::with(['campaign.category', 'payment'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('donor.dashboard', compact(
            'user',
            'totalSuccessAmount',
            'totalSuccessCount',
            'totalPendingCount',
            'totalAmenGiven',
            'recentDonations'
        ));
    }

    public function donations(Request $request): View
    {
        $user = $request->user();

        $query = Donation::with(['campaign.category', 'payment'])
            ->where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', (string) $request->query('status'));
        }

        $donations = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('donor.donations', compact('donations'));
    }

    public function showDonation(Request $request, Donation $donation): View
    {
        $user = $request->user();

        if ($donation->user_id === null || (int) $donation->user_id !== (int) $user->id) {
            abort(403, 'Anda tidak berhak melihat detail transaksi milik pengguna lain.');
        }

        $donation->load(['campaign.category', 'payment']);

        return view('donor.donation-show', compact('donation'));
    }

    public function profile(Request $request): View
    {
        return view('donor.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = trim($validated['name']);
        $user->email = strtolower(trim($validated['email']));
        $user->phone = $validated['phone'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return back()->with('success', 'Informasi profil akun Anda berhasil diperbarui.');
    }
}
