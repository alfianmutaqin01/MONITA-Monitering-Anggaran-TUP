<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


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
    View::composer('*', function ($view) {
        $userData = session('user_data') ?? [];
        $view->with('userRole', $userData['role'] ?? 'user')
             ->with('userUnitKode', $userData['kode_pp'] ?? '')
             ->with('userUnitNama', $userData['nama_pp'] ?? '')
             ->with('allUnits', [
                'LAB' => 'Bagian Laboratorium',
                'AKA' => 'Bagian Pelayanan Akademik Pusat',
                'INV' => 'Bagian Sentra Inovasi',
                // dst...
             ]);
    });
}
}
