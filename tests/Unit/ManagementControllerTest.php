<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ManagementController;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_ClearValuesRequest;

class ManagementControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Mock Facades
        Cache::shouldReceive('remember')->andReturn([]);
        File::shouldReceive('get')->andReturn('KEY="VALUE"');
        Hash::shouldReceive('make')->andReturn('hashed_password');
        
        // Mock Google Service
        $this->mockService = $this->createMock(Google_Service_Sheets::class);
        $this->mockService->spreadsheets_values = $this->createMock(\Google_Service_Sheets_Resource_SpreadsheetsValues::class);
        
        // Mock reindexNumbers calls to prevent actual API calls during store/destroy
        $this->mockService->spreadsheets_values->method('update')->willReturn(new Google_Service_Sheets_ValueRange());
        $this->mockService->spreadsheets_values->method('append')->willReturn(new Google_Service_Sheets_ValueRange());
        $this->mockService->spreadsheets_values->method('clear')->willReturn(new Google_Service_Sheets_ClearValuesRequest([]));
    }

    protected function getControllerInstance()
    {
        $controller = new ManagementController();
        // Inject mock service (using reflection for private constructor dependency)
        $reflection = new \ReflectionClass(ManagementController::class);
        $prop = $reflection->getProperty('service');
        $prop->setAccessible(true);
        $prop->setValue($controller, $this->mockService);
        
        $propId = $reflection->getProperty('spreadsheetId');
        $propId->setAccessible(true);
        $propId->setValue($controller, 'mock_id');
        
        return $controller;
    }

    /**
     * @test
     */
    public function it_can_store_a_new_user_and_reindex()
    {
        $controller = $this->getControllerInstance();
        
        // Mock getCachedUsers to simulate empty sheet initially
        $mockGet = $this->createMock(\Google_Service_Sheets_Resource_SpreadsheetsValues::class);
        $mockGet->method('get')->willReturnOnConsecutiveCalls(
            // 1st call: getCachedUsers (empty)
            (new \Google_Service_Sheets_ValueRange())->setValues([
                ['1', 'K01', 'Nama Lama', 'user_old', 'pass', 'admin'] // Existing row 1
            ]),
            // 2nd call: reindexNumbers (get data again)
            (new \Google_Service_Sheets_ValueRange())->setValues([
                ['1', 'K01', 'Nama Lama', 'user_old', 'pass', 'admin'],
                ['2', 'K_NEW', 'Nama Baru', 'user_new', 'pass', 'user'] // Row 2 is the one we add
            ])
        );
        $this->mockService->spreadsheets_values = $mockGet;

        $request = new Request([
            'kode_pp' => 'K_NEW',
            'nama_pp' => 'Nama Baru',
            'username' => 'user_new',
            'password' => 'new_password',
            'role' => 'user',
        ]);
        
        // Expect append and clear (which triggers reindexNumbers)
        $this->mockService->spreadsheets_values->expects($this->exactly(2))->method('update'); // One for reindex, one for destroy
        $this->mockService->spreadsheets_values->expects($this->once())->method('append');
        
        $response = $controller->store($request);
        
        $this->assertTrue($response->getData()['success']);
    }

    /**
     * @test
     */
    public function it_updates_user_data_and_hashes_password()
    {
        $controller = $this->getControllerInstance();
        
        // Mock findRowByKode result
        $mockRow = ['K001', 'OldKode', 'OldNama', 'OldUser', 'old_pass', 'admin'];
        $this->mockService->spreadsheets_values->method('get')->willReturnOnConsecutiveCalls(
            (new \Google_Service_Sheets_ValueRange())->setValues([$mockRow]) // First call to getCachedUsers
        );
        
        $request = new Request([
            'kode_pp' => 'K001', // Same code
            'nama_pp' => 'New Nama',
            'username' => 'OldUser', // Same username
            'password' => 'new_secret',
            'role' => 'admin',
        ]);
        
        // Expect update call (no reindex needed if kode_pp is the same)
        $this->mockService->spreadsheets_values->expects($this->once())->method('update');
        
        $response = $controller->update('OldKode', $request); // OldKode is the key to find
        
        $this->assertTrue($response->getData()['success']);
    }

    /**
     * @test
     */
    public function it_deletes_user_and_reindexes()
    {
        $controller = $this->getControllerInstance();
        
        // Mock findRowByKode result (Row 5 in sheet, which is index 4 in array)
        $mockRow = ['1', 'K001', 'Nama', 'user', 'pass', 'user'];
        $this->mockService->spreadsheets_values->method('get')->willReturnOnConsecutiveCalls(
            // 1st call: findRowByKode (getCachedUsers)
            (new \Google_Service_Sheets_ValueRange())->setValues([
                ['1', 'K001', 'Nama', 'user', 'pass', 'user'],
                ['2', 'K002', 'Nama2', 'user2', 'pass2', 'user'],
            ]),
            // 2nd call: reindexNumbers (getCachedUsers again)
            (new \Google_Service_Sheets_ValueRange())->setValues([
                ['1', 'K001', 'Nama', 'user', 'pass', 'user'],
                ['2', 'K002', 'Nama2', 'user2', 'pass2', 'user'],
            ])
        );

        // Expect clear and update (for reindexing)
        $this->mockService->spreadsheets_values->expects($this->exactly(2))->method('update'); 
        $this->mockService->spreadsheets_values->expects($this->once())->method('clear'); 
        
        $response = $controller->destroy('K001');
        
        $this->assertTrue($response->getData()['success']);
    }
}