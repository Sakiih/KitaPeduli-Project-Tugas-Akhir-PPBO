<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\PaymentResolverInterface;
use App\Payments\BankTransferPayment;
use App\Payments\PaymentMethodResolver;
use App\Payments\QrisPayment;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Ikat abstraksi resolver ke daftar metode pembayaran simulasi yang aktif.
        $this->app->singleton(PaymentResolverInterface::class, function ($app) {
            return new PaymentMethodResolver([
                $app->make(QrisPayment::class),
                $app->make(BankTransferPayment::class),
            ]);
        });
    }

    public function boot(): void
    {
        Paginator::useTailwind();
    }
}
