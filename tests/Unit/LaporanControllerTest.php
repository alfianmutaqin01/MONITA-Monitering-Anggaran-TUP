<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\LaporanController;
use Google_Service_Sheets;
use ReflectionClass;

class LaporanControllerTest extends TestCase
{
    // Mock Google Service Sheets
    protected function mockGoogleService($values = [])
    {
        $mockResponse = $this->createMock(\Google_Service_Sheets_ValueRange::class);
        $mockResponse->method('getValues')->willReturn($values);

        $mockService = $this->createMock(Google_Service_Sheets::class);
        $mockService->spreadsheets_values = $this->createMock(\Google_Service_Sheets_Resource_SpreadsheetsValues::class);
        $mockService->spreadsheets_values->method('get')->willReturn($mockResponse);
        
        return $mockService;
    }
    
    /**
     * @test
     */
    public function it_filters_data_correctly_by_unit_and_type()
    {
        $controller = new LaporanController();
        
        // Data mentah (Kolom B:Unit, Kolom C:Tipe, Kolom H:Anggaran, Kolom I:Realisasi, Kolom J:Saldo)
        $mockValues = [
            ['K01', 'UNIT_A', 'Operasional', '', '', '', '100', '50', '50'],
            ['K02', 'UNIT_B', 'Remun', '', '', '', '200', '100', '100'],
            ['K03', 'UNIT_A', 'Bang', '', '', '', '300', '150', '150'],
            ['K04', 'UNIT_A', 'NTF', '', '', '', '50', '50', '0'],
        ];
        $mockService = $this->mockGoogleService($mockValues);
        
        // Inject mock service (as done in previous tests)
        $reflectionController = new \ReflectionClass(LaporanController::class);
        $prop = $reflectionController->getProperty('service');
        $prop->setAccessible(true);
        $prop->setValue($controller, $mockService);
        $prop = $reflectionController->getProperty('spreadsheetId');
        $prop->setAccessible(true);
        $prop->setValue($controller, 'mock_id');

        // --- Test 1: Filter by Unit 'UNIT_A' ---
        $requestA = new Request(['unit' => 'UNIT_A', 'type' => 'all']);
        $responseA = $controller->index($requestA, 1);
        $dataA = $responseA->getOriginalContent()['data'];
        $this->assertCount(3, $dataA, "Seharusnya hanya 3 baris untuk UNIT_A.");
        $this->assertEquals('UNIT_A', $dataA[0]['unit']);
        $this->assertEquals('UNIT_A', $dataA[2]['unit']);

        // --- Test 2: Filter by Type 'operasional' for UNIT_A ---
        $requestOp = new Request(['unit' => 'UNIT_A', 'type' => 'operasional']);
        $responseOp = $controller->index($requestOp, 1);
        $dataOp = $responseOp->getOriginalContent()['data'];
        $this->assertCount(1, $dataOp, "Seharusnya hanya 1 baris Operasional untuk UNIT_A.");
        $this->assertEquals('Operasional', $dataOp[0]['tipe']);

        // --- Test 3: Filter by Type 'bang' for all units ---
        $requestBang = new Request(['unit' => null, 'type' => 'bang']);
        $responseBang = $controller->index($requestBang, 1);
        $dataBang = $responseBang->getOriginalContent()['data'];
        $this->assertCount(1, $dataBang, "Seharusnya hanya 1 baris Bang untuk semua unit.");
    }
}