<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\ExportController;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class ExportControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Mock PDF Facade (DomPDF)
        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('loadView')->andReturnSelf();
        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('setPaper')->andReturnSelf();
        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('download')->andReturnSelf();
        
        // Mock Carbon time
        $this->freezeTime('2024-06-15 10:00:00'); // Bulan Juni (TW 2)
    }

    protected function tearDown(): void
    {
        $this->unfreezeTime();
        parent::tearDown();
    }

    protected function mockGoogleService($values = [])
    {
        $mockResponse = $this->createMock(Google_Service_Sheets_ValueRange::class);
        $mockResponse->method('getValues')->willReturn($values);

        $mockService = $this->createMock(Google_Service_Sheets::class);
        $mockService->spreadsheets_values = $this->createMock(\Google_Service_Sheets_Resource_SpreadsheetsValues::class);
        $mockService->spreadsheets_values->method('get')->willReturn($mockResponse);
        return $mockService;
    }

    protected function getControllerInstance($mockService)
    {
        $controller = new ExportController();
        // Inject mock service
        $reflection = new \ReflectionClass(ExportController::class);
        $propId = $reflection->getProperty('spreadsheetId');
        $propId->setAccessible(true);
        $propId->setValue($controller, 'mock_id');
        $propService = $reflection->getProperty('service');
        $propService->setAccessible(true);
        $propService->setValue($controller, $mockService);
        return $controller;
    }

    /**
     * @test
     */
    public function it_exports_laporan_triwulan_with_filters()
    {
        $controller = $this->getControllerInstance($this->mockGoogleService([
            ['K01', 'UNIT_A', 'Operasional', '', '', '', '100', '50', '50'],
            ['K02', 'UNIT_B', 'Remun', '', '', '', '200', '100', '100'],
            ['K03', 'UNIT_A', 'Bang', '', '', '', '300', '150', '150'],
        ]));
        
        $request = new Request(['unit' => 'UNIT_A', 'type' => 'operasional']);
        
        // Expect download to be called
        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('download')->once()->with("Laporan_Triwulan_2_UNIT_A.pdf");

        $controller->laporanTriwulan($request, 2);
    }

    /**
     * @test
     */
    public function it_exports_detail_unit_correctly_for_current_tw()
    {
        $controller = $this->getControllerInstance($this->mockGoogleService([
            ['UNIT_X', 'Operasional', '', '', '', '', '100', '10', '90'],
            ['UNIT_X', 'Bang', '', '', '', '', '200', '100', '100'],
        ]));
        
        $request = new Request(['type' => 'all']);
        
        // Expect download to be called
        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('download')->once()->with("Detail_Unit_UNIT_X_TW2.pdf");

        $controller->detailUnit($request, 'UNIT_X');
    }

    /**
     * @test
     */
    public function it_exports_summary_rka_view()
    {
        $controller = $this->getControllerInstance($this->mockGoogleService([
            ['PP001', 'Unit A', 'Bidang X', '1000000', '500000', '500000', '50%'],
        ]));
        
        // Expect loadView to be called with 'exports.summary-rka' and landscape orientation
        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('loadView')->once()->with('exports.summary-rka', \Mockery::any())
            ->andReturnSelf();
        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('setPaper')->once()->with('a4', 'landscape');
        
        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('download')->once()->with("Summary_TW2_rka.pdf");

        $controller->summary(2, 'rka');
    }
}