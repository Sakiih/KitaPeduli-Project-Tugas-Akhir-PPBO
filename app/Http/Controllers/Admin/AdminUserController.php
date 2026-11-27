<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::withCount('donations');

        if ($request->filled('role')) {
            $query->where('role', (string) $request->query('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->query('status'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['donations' => function ($q) {
            $q->with(['campaign', 'payment'])->orderByDesc('created_at')->limit(20);
        }])->loadCount('donations');

        return view('admin.users.show', compact('user'));
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        if ((int) $user->id === (int) $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Admin Anda sendiri.');
        }

        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in([User::STATUS_ACTIVE, User::STATUS_INACTIVE]),
            ],
        ]);

        if ($validated['status'] === User::STATUS_ACTIVE) {
            $user->activate();
        } else {
            $user->deactivate();
            $user->tokens()->delete();
        }

        return back()->with('success', 'Status akun '.$user->name.' diubah menjadi '.$user->status.'.');
    }
}
