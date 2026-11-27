<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AdminDashboardApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CampaignApiController;
use App\Http\Controllers\Api\CampaignUpdateApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\DisbursementReportApiController;
use App\Http\Controllers\Api\DonationApiController;
use App\Http\Controllers\Api\DonationReactionApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\UserManagementApiController;
use Illuminate\Support\Facades\Route;

// Rute autentikasi
Route::post('/register', [AuthApiController::class, 'register'])->name('api.register');
Route::post('/login', [AuthApiController::class, 'login'])->name('api.login');

Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout'])->name('api.logout');
    Route::get('/me', [AuthApiController::class, 'me'])->name('api.me');
});

// Rute publik kampanye dan kategori
Route::get('/campaigns', [CampaignApiController::class, 'index'])->name('api.campaigns.index');
Route::get('/campaigns/{id}', [CampaignApiController::class, 'show'])->name('api.campaigns.show');
Route::get('/categories', [CategoryApiController::class, 'index'])->name('api.categories.index');

// Rute transaksi donasi dan doa publik
Route::post('/donations', [DonationApiController::class, 'store'])->name('api.donations.store');
Route::get('/campaigns/{id}/donations', [DonationApiController::class, 'campaignPublicDonations'])
    ->whereNumber('id')
    ->name('api.campaigns.donations');

Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::get('/donations', [DonationApiController::class, 'myDonations'])->name('api.donations.mine');
    Route::get('/donations/{id}', [DonationApiController::class, 'show'])
        ->whereNumber('id')
        ->name('api.donations.show');
});

// Rute simulasi pembayaran
Route::get('/payments/{reference}', [PaymentApiController::class, 'show'])->name('api.payments.show');
Route::post('/payments/{reference}/simulate-success', [PaymentApiController::class, 'simulateSuccess'])
    ->name('api.payments.simulate-success');

// Rute kabar terbaru dan laporan penyaluran publik
Route::get('/campaigns/{id}/updates', [CampaignUpdateApiController::class, 'index'])
    ->whereNumber('id')
    ->name('api.campaigns.updates.index');
Route::get('/campaigns/{id}/disbursement-reports', [DisbursementReportApiController::class, 'index'])
    ->whereNumber('id')
    ->name('api.campaigns.disbursements.index');

// Rute reaksi Aamiin donatur terdaftar
Route::middleware(['auth:sanctum', 'active', 'donor'])->group(function () {
    Route::post('/donations/{id}/amen', [DonationReactionApiController::class, 'store'])
        ->whereNumber('id')
        ->name('api.donations.amen.store');
    Route::delete('/donations/{id}/amen', [DonationReactionApiController::class, 'destroy'])
        ->whereNumber('id')
        ->name('api.donations.amen.destroy');
});

// Rute administrasi Admin
Route::prefix('admin')->middleware(['auth:sanctum', 'active', 'admin'])->group(function () {
    Route::get('/campaigns', [CampaignApiController::class, 'adminIndex'])->name('api.admin.campaigns.index');
    Route::post('/campaigns', [CampaignApiController::class, 'store'])->name('api.admin.campaigns.store');
    Route::put('/campaigns/{id}', [CampaignApiController::class, 'update'])
        ->whereNumber('id')
        ->name('api.admin.campaigns.update');
    Route::patch('/campaigns/{id}/status', [CampaignApiController::class, 'updateStatus'])
        ->whereNumber('id')
        ->name('api.admin.campaigns.status');
    Route::delete('/campaigns/{id}', [CampaignApiController::class, 'destroy'])
        ->whereNumber('id')
        ->name('api.admin.campaigns.destroy');

    Route::post('/categories', [CategoryApiController::class, 'store'])->name('api.admin.categories.store');
    Route::put('/categories/{id}', [CategoryApiController::class, 'update'])
        ->whereNumber('id')
        ->name('api.admin.categories.update');
    Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy'])
        ->whereNumber('id')
        ->name('api.admin.categories.destroy');

    Route::get('/donations', [DonationApiController::class, 'adminIndex'])->name('api.admin.donations.index');
    Route::get('/payments', [PaymentApiController::class, 'adminIndex'])->name('api.admin.payments.index');

    Route::post('/campaigns/{id}/updates', [CampaignUpdateApiController::class, 'store'])
        ->whereNumber('id')
        ->name('api.admin.updates.store');
    Route::put('/updates/{id}', [CampaignUpdateApiController::class, 'update'])
        ->whereNumber('id')
        ->name('api.admin.updates.update');
    Route::delete('/updates/{id}', [CampaignUpdateApiController::class, 'destroy'])
        ->whereNumber('id')
        ->name('api.admin.updates.destroy');

    Route::post('/campaigns/{id}/disbursement-reports', [DisbursementReportApiController::class, 'store'])
        ->whereNumber('id')
        ->name('api.admin.disbursements.store');
    Route::put('/disbursement-reports/{id}', [DisbursementReportApiController::class, 'update'])
        ->whereNumber('id')
        ->name('api.admin.disbursements.update');
    Route::delete('/disbursement-reports/{id}', [DisbursementReportApiController::class, 'destroy'])
        ->whereNumber('id')
        ->name('api.admin.disbursements.destroy');

    Route::get('/dashboard', [AdminDashboardApiController::class, 'dashboard'])->name('api.admin.dashboard');
    Route::get('/reports/donations', [AdminDashboardApiController::class, 'donationReport'])->name('api.admin.reports.donations');

    Route::get('/users', [UserManagementApiController::class, 'index'])->name('api.admin.users.index');
    Route::get('/users/{id}', [UserManagementApiController::class, 'show'])
        ->whereNumber('id')
        ->name('api.admin.users.show');
    Route::patch('/users/{id}/status', [UserManagementApiController::class, 'updateStatus'])
        ->whereNumber('id')
        ->name('api.admin.users.status');
});
