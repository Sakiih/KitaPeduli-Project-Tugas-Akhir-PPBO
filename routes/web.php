<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminCampaignController;
use App\Http\Controllers\Admin\AdminCampaignUpdateController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDisbursementController;
use App\Http\Controllers\Admin\AdminDonationController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\Donor\DonorDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Rute halaman publik dan penjelajahan kampanye
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/kampanye', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/kampanye/{slug}', [CampaignController::class, 'show'])->name('campaigns.show');

// Rute autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Rute donasi dan simulasi pembayaran
Route::get('/kampanye/{slug}/donasi', [DonationController::class, 'create'])->name('donations.create');
Route::post('/kampanye/{slug}/donasi', [DonationController::class, 'store'])->name('donations.store');

Route::get('/pembayaran/{reference}', [PaymentController::class, 'show'])->name('payments.show');
Route::post('/pembayaran/{reference}/simulasi-berhasil', [PaymentController::class, 'simulateSuccess'])->name('payments.simulate');

// Rute reaksi Aamiin donatur terdaftar
Route::post('/donasi/{donation}/aamiin', [DonationController::class, 'toggleAmen'])
    ->middleware(['auth', 'active', 'donor'])
    ->name('donations.amen.toggle');

// Rute dashboard dan riwayat donatur terdaftar
Route::prefix('donatur')->name('donor.')->middleware(['auth', 'active', 'donor'])->group(function () {
    Route::get('/dashboard', [DonorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/riwayat-donasi', [DonorDashboardController::class, 'donations'])->name('donations');
    Route::get('/riwayat-donasi/{donation}', [DonorDashboardController::class, 'showDonation'])->name('donations.show');
    Route::get('/profil', [DonorDashboardController::class, 'profile'])->name('profile');
    Route::put('/profil', [DonorDashboardController::class, 'updateProfile'])->name('profile.update');
});

// Rute panel administrator
Route::prefix('admin')->name('admin.')->middleware(['auth', 'active', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/kampanye', [AdminCampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/kampanye/buat', [AdminCampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/kampanye', [AdminCampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/kampanye/{campaign}/edit', [AdminCampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/kampanye/{campaign}', [AdminCampaignController::class, 'update'])->name('campaigns.update');
    Route::patch('/kampanye/{campaign}/status', [AdminCampaignController::class, 'updateStatus'])->name('campaigns.status');
    Route::delete('/kampanye/{campaign}', [AdminCampaignController::class, 'destroy'])->name('campaigns.destroy');

    Route::get('/kategori', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/kategori', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/kategori/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/kategori/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/kabar-terbaru', [AdminCampaignUpdateController::class, 'index'])->name('updates.index');
    Route::post('/kabar-terbaru', [AdminCampaignUpdateController::class, 'store'])->name('updates.store');
    Route::put('/kabar-terbaru/{update}', [AdminCampaignUpdateController::class, 'update'])->name('updates.update');
    Route::delete('/kabar-terbaru/{update}', [AdminCampaignUpdateController::class, 'destroy'])->name('updates.destroy');

    Route::get('/penyaluran-dana', [AdminDisbursementController::class, 'index'])->name('disbursements.index');
    Route::post('/penyaluran-dana', [AdminDisbursementController::class, 'store'])->name('disbursements.store');
    Route::put('/penyaluran-dana/{report}', [AdminDisbursementController::class, 'update'])->name('disbursements.update');
    Route::delete('/penyaluran-dana/{report}', [AdminDisbursementController::class, 'destroy'])->name('disbursements.destroy');

    Route::get('/transaksi-donasi', [AdminDonationController::class, 'index'])->name('donations.index');

    Route::get('/pengguna', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/pengguna/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/pengguna/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.status');

    Route::get('/laporan', [AdminReportController::class, 'index'])->name('reports.index');

    Route::get('/profil', [AdminProfileController::class, 'edit'])->name('profile');
    Route::put('/profil', [AdminProfileController::class, 'update'])->name('profile.update');
});
