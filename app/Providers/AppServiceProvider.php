<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
        Paginator::useTailwind();
    }

    // Gunakan kode di bawah ini jika ingin memaksa penggunaan HTTPS pada lingkungan production

    // public function boot()
    // {
    //     Carbon::setlocale('id');
    //     setlocale(LC_TIME, 'id_ID');
    //     Paginator::useTailwind();
    //     if (app()->environment('production')) {
    //         URL::forceScheme('https');
    //     }
    // }
}
