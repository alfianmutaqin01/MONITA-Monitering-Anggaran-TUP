<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GoogleSheetService::class, function ($app) {
            return new GoogleSheetService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share data ke semua view
        View::composer('*', function ($view) {
            try {
                // Ambil data units dari Google Sheets
                $googleSheetService = app(GoogleSheetService::class);
                $unitsFromSheet = $googleSheetService->getUnitsFromSheet();
                
                // Gunakan data dari Google Sheets, fallback ke config
                $allUnits = !empty($unitsFromSheet) ? $unitsFromSheet : config('units', []);
                
            } catch (\Exception $e) {
                \Log::error('Error loading units from Google Sheets: ' . $e->getMessage());
                // Fallback ke config file
                $allUnits = config('units', []);
            }

            // Ambil data user dari session
            $userData = session('user_data', []);
            $userRole = session('user_role', 'user');
            $userUnitKode = $userData['kode_pp'] ?? null;
            $userUnitNama = $userData['nama_pp'] ?? null;

            // Cari nama unit dari kode jika tidak ada di session
            if ($userUnitKode && !$userUnitNama && isset($allUnits[$userUnitKode])) {
                $userUnitNama = $allUnits[$userUnitKode];
            }

            $view->with([
                'allUnits'     => $allUnits,
                'userRole'     => $userRole,
                'userUnitKode' => $userUnitKode,
                'userUnitNama' => $userUnitNama,
            ]);
        });
        
        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            // Gagal jika sukses bukan true atau skor di bawah ambang batas (misal: 0.5)
            if (!($result['success'] ?? false) || ($result['score'] ?? 0) < 0.5) {
                return false;
            }

            return true;
        });
    }
}