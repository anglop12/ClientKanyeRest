<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private $typeAuthorization = 'Bearer ';
    private $header = ['Accept' => 'application/json'];

    private $routeLogin = '/api/login';
    private $userEmail = 'admin@admin.com';
    private $userPass = 'admin';

    public function setUp() : void
    {
        parent::setUp();
        Artisan::call('migrate',['-vvv' => true]);
        Artisan::call('passport:install',['-vvv' => true]);
        Artisan::call('db:seed',['-vvv' => true]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $body = [
            'email' => $this->userEmail,
            'password' => $this->userPass
        ];
        $responseLogin = $this->postJson($this->routeLogin, $body, $this->header);
        $responseLogin->assertStatus(200);
        $responseLogin->assertJsonStructure(['user','access_token']);

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register()
    {
        $body = [
            'name' => 'test user',
            'email' => 'user@test.com',
            'password' => bcrypt('userTest'),
        ];
        $responseLogin = $this->postJson('/api/register', $body, $this->header);
        $responseLogin->assertStatus(200);
        $responseLogin->assertJsonStructure(['user','access_token']);
        $responseLogin
            ->assertJsonFragment([
                'name' => 'test user',
                'email' => 'user@test.com',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_logout()
    {
        $body = [
            'email' => $this->userEmail,
            'password' => $this->userPass
        ];
        $responseLogin = $this->postJson($this->routeLogin, $body, $this->header);
        $responseLogin->assertStatus(200);
        $responseLogin->assertJsonStructure(['user','access_token']);

        $token = $responseLogin['access_token'];

        $responseUser = $this->withHeaders(['Authorization' => $this->typeAuthorization . $token])->post('/api/logout');
        $responseUser->assertStatus(200);
        $responseUser->assertJsonStructure(['message']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user()
    {
        $body = [
            'email' => $this->userEmail,
            'password' => $this->userPass
        ];
        $responseLogin = $this->postJson($this->routeLogin, $body, $this->header);
        $responseLogin->assertStatus(200);
        $responseLogin->assertJsonStructure(['user','access_token']);

        $token = $responseLogin['access_token'];

        $responseUser = $this->withHeaders([
            'Authorization' => $this->typeAuthorization . $token
        ])->getJson('/api/user');
        $responseUser->assertStatus(200);
        $responseUser->assertJsonStructure([
            'id',
            'name',
            'role',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at'
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_edit_user()
    {
        $body = [
            'email' => $this->userEmail,
            'password' => $this->userPass
        ];
        $responseLogin = $this->postJson($this->routeLogin, $body, $this->header);
        $responseLogin->assertStatus(200);
        $responseLogin->assertJsonStructure(['user','access_token']);

        $token = $responseLogin['access_token'];
        $userId = $responseLogin['user']['id'];

        $bodyEdit = [
            'email' => 'admin@edit.com',
            'name' => 'admin edit'
        ];

        $responseUser = $this->withHeaders([
            'Authorization' => $this->typeAuthorization . $token
        ])->postJson("/api/editUser/$userId", $bodyEdit, $this->header);
        $responseUser->assertStatus(200);
        $responseUser->assertJsonStructure(['user','access_token']);
        $responseUser->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'role',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at'
            ],
        ]);
        $responseUser
            ->assertJsonFragment([
                'email' => 'admin@edit.com',
                'name' => 'admin edit'
            ]);
    }
}
