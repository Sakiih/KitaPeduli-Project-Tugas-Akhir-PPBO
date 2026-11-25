<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementApiController extends Controller
{
    public function index(Request $request): JsonResponse
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

        $perPage = min(50, max(1, (int) $request->query('per_page', 15)));
        $users = $query->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengguna berhasil diambil.',
            'data' => $users->getCollection(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $user = ctype_digit($id)
            ? User::with(['donations' => function ($query) {
                $query->with(['campaign:id,title,slug', 'payment'])->orderByDesc('created_at')->limit(15);
            }])->withCount('donations')->find((int) $id)
            : null;

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pengguna berhasil diambil.',
            'data' => $user,
        ]);
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $user = ctype_digit($id) ? User::find((int) $id) : null;
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        if ((int) $user->id === (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menonaktifkan akun Admin Anda sendiri.',
                'data' => null,
            ], 422);
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

        return response()->json([
            'success' => true,
            'message' => 'Status akun pengguna berhasil diubah menjadi '.$user->status.'.',
            'data' => $user,
        ]);
    }
}
