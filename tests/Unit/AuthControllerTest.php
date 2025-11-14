<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Services\GoogleSheetService;
use App\Http\Controllers\AuthController;

class AuthControllerTest extends TestCase
{
    protected $mockSheetService;

    protected function setUp(): void
    {
        parent::setUp();
        // Mock GoogleSheetService
        $this->mockSheetService = $this->createMock(GoogleSheetService::class);
        
        // Mock Hash::check untuk pengujian login ter-hash
        Hash::shouldReceive('check')->andReturn(true); 
        Hash::shouldReceive('info')->andReturn(['algoName' => 'bcrypt']);
        
        // Mock Session
        Session::start();
    }

    private function getControllerInstance()
    {
        return new AuthController($this->mockSheetService);
    }

    /**
     * @test
     */
    public function it_logs_in_user_successfully_with_hashed_password()
    {
        $controller = $this->getControllerInstance();
        $request = new Request(['username' => 'admin_user', 'password' => 'secret123']);

        // Mock data user dari sheet (password sudah di-hash)
        $mockUsers = [
            [
                'kode_pp' => 'ADM01',
                'username' => 'admin_user',
                'password' => '$2y$10$hashed_password_here', // Hash palsu
                'role' => 'admin'
            ]
        ];
        $this->mockSheetService->method('getUsersFromSheet')->willReturn($mockUsers);
        
        // Mock Hash::check untuk mengembalikan true saat password cocok
        Hash::shouldReceive('check')->once()->with('secret123admin_user', '$2y$10$hashed_password_here')->andReturn(true);
        
        // Mock clear cache
        $this->mockSheetService->expects($this->once())->method('clearUnitsCache');

        $response = $controller->login($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('dashboard'), $response->headers->get('Location'));
        $this->assertTrue(Session::get('user_authenticated'));
        $this->assertEquals('admin', Session::get('user_role'));
    }

    /**
     * @test
     */
    public function it_fails_login_with_wrong_credentials()
    {
        $controller = $this->getControllerInstance();
        $request = new Request(['username' => 'wrong_user', 'password' => 'wrong_pass']);

        // Mock data user dari sheet
        $mockUsers = [
            [
                'kode_pp' => 'USER01',
                'username' => 'valid_user',
                'password' => 'plain_password', // Plain text
                'role' => 'user'
            ]
        ];
        $this->mockSheetService->method('getUsersFromSheet')->willReturn($mockUsers);

        // Mock Hash::check untuk mengembalikan false (atau tidak dipanggil jika plain text)
        Hash::shouldReceive('check')->andReturn(false); 
        
        $response = $controller->login($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertSessionHasErrors(['login' => 'Username atau password salah.']);
        $this->assertFalse(Session::get('user_authenticated'));
    }

    /**
     * @test
     */
    public function it_logs_out_user_correctly()
    {
        Session::put('user_authenticated', true);
        Session::put('user_data', ['name' => 'Test User']);
        
        $controller = $this->getControllerInstance();
        $response = $controller->logout();

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertFalse(Session::get('user_authenticated'));
        $this->assertNull(Session::get('user_data'));
    }
}