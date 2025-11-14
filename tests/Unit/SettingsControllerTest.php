<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\SettingsController;

class SettingsControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Pastikan kita tidak benar-benar menulis ke file .env saat testing
        File::shouldReceive('put')->andReturn(true);
        File::shouldReceive('exists')->andReturn(true);
    }

    /**
     * @test
     */
    public function it_can_set_env_value_correctly()
    {
        $controller = new SettingsController();
        $envContent = "APP_NAME=\"Old Name\"\nGOOGLE_SPREADSHEET_ID=\"old_id\"";
        
        // Test update existing key
        $newContent = $controller->setEnvValue('APP_NAME', 'New Name', $envContent);
        $this->assertStringContainsString('APP_NAME="New Name"', $newContent);
        $this->assertStringNotContainsString('APP_NAME="Old Name"', $newContent);

        // Test add new key
        $newContent = $controller->setEnvValue('NEW_KEY', 'new_value', $newContent);
        $this->assertStringContainsString('NEW_KEY="new_value"', $newContent);
    }

    /**
     * @test
     */
    public function it_handles_spreadsheet_update_and_override_logic()
    {
        $controller = new SettingsController();
        $envContent = "GOOGLE_SPREADSHEET_ID_YEAR_2024=\"old_id_2024\"\nACTIVE_YEAR=2023";
        
        // --- Test 1: Tambah Tahun Baru ---
        $requestNew = new Request([
            'sheet_link' => 'https://docs.google.com/spreadsheets/d/new_id_2025/edit',
            'year' => '2025',
        ]);
        
        // Mock File::put to capture the final content
        File::shouldReceive('put')->once()->with(
            base_path('.env'), 
            \Mockery::on(function ($content) {
                return str_contains($content, 'GOOGLE_SPREADSHEET_ID_YEAR_2025="new_id_2025"');
            })
        )->andReturn(true);

        $response = $controller->update($requestNew);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertSessionHas('success', 'Spreadsheet tahun 2025 berhasil disimpan.');
        
        // --- Test 2: Override Tahun yang Sudah Ada ---
        Session::start();
        $requestOverride = new Request([
            'sheet_link' => 'https://docs.google.com/spreadsheets/d/override_id_2024/edit',
            'year' => '2024',
        ]);

        $controller->update($requestOverride);
        
        // Harusnya redirect ke halaman yang sama dengan pesan warning (karena belum ada konfirmasi)
        $this->assertSessionHas('warning', 'Tahun 2024 sudah ada. Apakah Anda yakin ingin menimpa ID Spreadsheet?');
        $this->assertSessionHas('show_override_modal', true);
    }
}