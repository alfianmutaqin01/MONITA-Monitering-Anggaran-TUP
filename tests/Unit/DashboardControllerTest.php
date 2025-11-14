<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\DashboardController;
use Google_Service_Sheets;
use Google_Service_Sheets_BatchGetValuesResponse;
use Google_Service_Sheets_ValueRange;
use ReflectionClass;

class DashboardControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Freeze time untuk pengujian default TW
        $this->freezeTime('2024-05-15 10:00:00'); // Bulan Mei -> Default TW 2
    }

    protected function tearDown(): void
    {
        $this->unfreezeTime();
        parent::tearDown();
    }

    // Mock Google Service Sheets (BatchGet)
    protected function mockGoogleBatchService($kodeValues, $serapanValues, $rkaValues, $operasionalValues)
    {
        $mockService = $this->createMock(Google_Service_Sheets::class);
        $mockService->spreadsheets_values = $this->createMock(\Google_Service_Sheets_Resource_SpreadsheetsValues::class);
        
        // Mock ValueRange for each range
        $mockVR1 = $this->createMock(Google_Service_Sheets_ValueRange::class);
        $mockVR1->method('getValues')->willReturn($kodeValues);
        
        $mockVR2 = $this->createMock(Google_Service_Sheets_ValueRange::class);
        $mockVR2->method('getValues')->willReturn($serapanValues);

        $mockVR3 = $this->createMock(Google_Service_Sheets_ValueRange::class);
        $mockVR3->method('getValues')->willReturn($rkaValues);

        $mockVR4 = $this->createMock(Google_Service_Sheets_ValueRange::class);
        $mockVR4->method('getValues')->willReturn($operasionalValues);

        // Mock Batch Response
        $mockBatchResponse = $this->createMock(Google_Service_Sheets_BatchGetValuesResponse::class);
        $mockBatchResponse->method('getValueRanges')->willReturn([
            $mockVR1, $mockVR2, $mockVR3, $mockVR4
        ]);

        $mockService->spreadsheets_values->method('batchGet')->willReturn($mockBatchResponse);

        // Mock getCellValue (for Saldo TW)
        $mockGetValueResponse = $this->createMock(\Google_Service_Sheets_ValueRange::class);
        $mockGetValueResponse->method('getValues')->willReturn([['1000000']]);
        $mockService->spreadsheets_values->method('get')->willReturn($mockGetValueResponse);

        return $mockService;
    }

    /**
     * @test
     */
    public function it_calculates_default_tw_and_normalizes_percentages()
    {
        $controller = new DashboardController();
        
        // Test normalizePercent logic
        $this->assertEquals(85.50, $controller->normalizePercent('85.5%'));
        $this->assertEquals(10.00, $controller->normalizePercent(0.1)); // 0.1 -> 10%
        $this->assertEquals(100.00, $controller->normalizePercent('1')); // 1 -> 100%
        $this->assertEquals(50.00, $controller->normalizePercent('50,00')); 
        $this->assertEquals(0.00, $controller->normalizePercent('')); 
        
        // Test default TW (May is TW 2)
        $request = new Request();
        $this->assertEquals(2, $controller->getDefaultTwForRequest($request));
    }

    /**
     * @test
     */
    public function it_fetches_and_structures_dashboard_data()
    {
        $controller = new DashboardController();
        
        // Data Mocking
        $kodeValues = [['PP001'], ['PP002'], [''], ['PP003']]; // PP003 is last, empty row in between
        $serapanValues = [['85.5%'], ['20'], [''], ['0.5']];
        $rkaValues = [['90'], [''], [''], ['10']];
        $operasionalValues = [['75.0'], ['50.0'], [''], ['5.0']];

        $mockService = $this->mockGoogleBatchService(
            $kodeValues, $serapanValues, $rkaValues, $operasionalValues
        );
        
        // Inject mock service
        $reflectionController = new \ReflectionClass(DashboardController::class);
        $prop = $reflectionController->getProperty('spreadsheetId');
        $prop->setAccessible(true);
        $prop->setValue($controller, 'mock_id');
        $prop = $reflectionController->getProperty('service');
        $prop->setAccessible(true);
        $prop->setValue($controller, $mockService);

        $request = new Request(['tw' => 2]);
        $response = $controller->index($request);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $viewData = $response->getOriginalContent();
        
        $this->assertEquals(2, $viewData['currentTw']);
        $this->assertEquals(['PP001', 'PP002', 'PP003'], $viewData['labels']);
        $this->assertEquals([85.50, 20.00, 50.00], $viewData['dataSerapan']); // 0.5 -> 50%
        $this->assertEquals([90.00, 0.00, 10.00], $viewData['dataRka']); 
        $this->assertEquals([75.00, 50.00, 5.00], $viewData['dataOperasional']); 
        $this->assertEquals(1000000.0, $viewData['saldoTW2']); // Dari mock getCellValue
    }
}