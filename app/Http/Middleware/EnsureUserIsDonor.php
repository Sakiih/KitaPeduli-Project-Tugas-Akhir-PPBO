<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsDonor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login sebagai Donatur Terdaftar.',
                    'data' => null,
                ], 401);
            }

            return redirect()->route('login');
        }

        if (! $user->isActive()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda sedang dinonaktifkan oleh Admin.',
                    'data' => null,
                ], 403);
            }

            abort(403, 'Akun Anda sedang dinonaktifkan oleh Admin.');
        }

        if (! $user->isDonor()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Fitur ini khusus untuk Donatur Terdaftar.',
                    'data' => null,
                ], 403);
            }

            abort(403, 'Fitur ini khusus untuk akun Donatur Terdaftar.');
        }

        return $next($request);
    }
}
