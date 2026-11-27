<?php

declare(strict_types=1);

use App\Http\Middleware\EnsureActiveUser;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsDonor;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'donor' => EnsureUserIsDonor::class,
            'active' => EnsureActiveUser::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $isApiRequest = static fn (Request $request): bool => $request->is('api/*') || $request->expectsJson();

        $exceptions->shouldRenderJsonWhen(fn (Request $request, Throwable $e): bool => $isApiRequest($request));

        $exceptions->render(function (AuthenticationException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login dan sertakan token autentikasi yang valid.',
                    'data' => null,
                ], 401);
            }
        });

        $exceptions->render(function (AuthorizationException|AccessDeniedHttpException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses sumber daya ini.',
                    'data' => null,
                ], 403);
            }
        });

        $exceptions->render(function (ValidationException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang dikirim tidak valid.',
                    'errors' => $e->errors(),
                    'data' => null,
                ], 422);
            }
        });

        $exceptions->render(function (ModelNotFoundException|NotFoundHttpException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data atau endpoint tidak ditemukan.',
                    'data' => null,
                ], 404);
            }
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Metode HTTP tidak diizinkan untuk endpoint ini.',
                    'data' => null,
                ], 405);
            }
        });

        $exceptions->render(function (QueryException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                $sqlState = $e->errorInfo[0] ?? $e->getCode();
                $driverCode = (int) ($e->errorInfo[1] ?? 0);
                if ($sqlState === '23000' || in_array($driverCode, [1451, 1452], true)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Operasi ditolak karena data masih terhubung dengan data lain.',
                        'data' => null,
                    ], 409);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan pada basis data.',
                    'data' => null,
                ], 500);
            }
        });

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                $statusCode = $e->getStatusCode();

                return response()->json([
                    'success' => false,
                    'message' => $statusCode >= 500
                        ? 'Terjadi kesalahan pada server.'
                        : ($e->getMessage() ?: 'Permintaan tidak dapat diproses.'),
                    'data' => null,
                ], $statusCode);
            }
        });

        $exceptions->render(function (Throwable $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan pada server.',
                    'data' => null,
                ], 500);
            }
        });
    })->create();
