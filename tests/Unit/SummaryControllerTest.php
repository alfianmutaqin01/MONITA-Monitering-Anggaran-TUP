<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\SummaryController;
use Google_Service_Sheets;
use Google_Service_Sheets_SheetProperties;
use Google_Service_Sheets_Sheet;
use ReflectionClass;

class SummaryControllerTest extends TestCase
{
    // Mock Google Service Sheets
    protected function mockGoogleService($values = [], $sheetExists = true)
    {
        $mockService = $this->createMock(Google_Service_Sheets::class);
        $mockService->spreadsheets_values = $this->createMock(\Google_Service_Sheets_Resource_SpreadsheetsValues::class);
        
        // Mock get() for data retrieval
        $mockResponse = $this->createMock(\Google_Service_Sheets_ValueRange::class);
        $mockResponse->method('getValues')->willReturn($values);
        $mockService->spreadsheets_values->method('get')->willReturn($mockResponse);
        
        // Mock get() for sheet list (to check if sheet exists)
        if ($sheetExists) {
            $sheetProps = $this->createMock(Google_Service_Sheets_SheetProperties::class);
            $sheetProps->method('getTitle')->willReturn('SUMMARY TW I');
            $sheet = $this->createMock(Google_Service_Sheets_Sheet::class);
            $sheet->method('getProperties')->willReturn($sheetProps);
            
            $sheetsList = [$sheet];
        } else {
            $sheetsList = [];
        }

        $mockSheets = $this->createMock(\Google_Service_Sheets_BatchGetValuesResponse::class);
        $mockSheets->method('getSheets')->willReturn($sheetsList);
        
        $mockService->method('spreadsheets')->willReturn($this->createMock(\Google_Service_Sheets_Resource_Spreadsheets::class));
        $mockService->spreadsheets->method('get')->willReturn($mockSheets);

        return $mockService;
    }

    /**
     * @test
     */
    public function it_redirects_to_settings_if_sheet_tab_is_missing()
    {
        $controller = new SummaryController();
        $mockService = $this->mockGoogleService([], false); // Sheet does not exist

        // Mock the service injection (using reflection for private method)
        $reflection = new ReflectionClass(SummaryController::class);
        $method = $reflection->getMethod('getGoogleSheetService');
        $method->setAccessible(true);
        $method->invoke($controller); // Initialize service property in controller instance

        // Override the service property directly for testing purposes (since constructor is not called)
        $reflectionController = new \ReflectionClass(SummaryController::class);
        $prop = $reflectionController->getProperty('service');
        $prop->setAccessible(true);
        $prop->setValue($controller, $mockService);

        Session::start();
        $request = Request::create('/summary/1', 'GET');
        $response = $controller->index(1);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertSessionHas('warning', "Spreadsheet tahun ini belum memiliki tab 'SUMMARY TW I'. Silakan tambahkan tab tersebut terlebih dahulu di Google Sheets.");
    }

    /**
     * @test
     */
    public function it_parses_data_correctly_and_returns_view()
    {
        $controller = new SummaryController();
        
        // Data palsu dari sheet (Hanya perlu beberapa baris untuk diuji)
        $mockValues = [
            ['PP001', 'Unit A', 'Bidang X', '1000000', '500000', '500000', '50%', '200000', '100000', '100000', '25%'],
            ['PP002', 'Unit B', 'Bidang Y', '500000', '100000', '400000', '20%', '', '', '', ''],
        ];
        $mockService = $this->mockGoogleService($mockValues);
        
        // Mock the service property
        $reflectionController = new \ReflectionClass(SummaryController::class);
        $prop = $reflectionController->getProperty('service');
        $prop->setAccessible(true);
        $prop = $reflectionController->getProperty('spreadsheetId');
        $prop->setAccessible(true);
        $prop->setValue($controller, 'mock_id');
        
        $prop = $reflectionController->getProperty('service');
        $prop->setAccessible(true);
        $prop->setValue($controller, $mockService);

        $request = new Request();
        $response = $controller->index(1);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $data = $response->getOriginalContent()['data'];
        
        $this->assertCount(2, $data);
        $this->assertEquals('PP001', $data[0]['kode_pp']);
        $this->assertEquals(1000000.0, $data[0]['anggaran_tw']); // Pastikan parseNumber bekerja
        $this->assertEquals(500000.0, $data[1]['saldo_tw']);
    }
}