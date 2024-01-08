<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $typeAuthorization = 'Bearer ';
    private $header = ['Accept' => 'application/json'];

    private $routeLogin = '/api/login';
    private $userEmail = 'admin@admin.com';
    private $userPass = 'admin';
    private $token = '';

    public function setUp() : void
    {
        parent::setUp();
        Artisan::call('migrate',['-vvv' => true]);
        Artisan::call('passport:install',['-vvv' => true]);
        Artisan::call('db:seed',['-vvv' => true]);

        $body = [
            'email' => $this->userEmail,
            'password' => $this->userPass
        ];
        $responseLogin = $this->postJson($this->routeLogin, $body, $this->header);
        $responseLogin->assertStatus(200);
        $responseLogin->assertJsonStructure(['user','access_token']);

        $this->token = $responseLogin['access_token'];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_users()
    {
        User::create([
            'name' => 'Angel Test',
            'email' => ('angel@test.com'),
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => bcrypt('angelTest'),
        ]);
        User::create([
            'name' => 'Rebe Test',
            'email' => ('rebe@test.com'),
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => bcrypt('rebeTest'),
        ]);
        User::create([
            'name' => 'Eri Test',
            'email' => ('eri@test.com'),
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => bcrypt('eriTest'),
        ]);
        User::create([
            'name' => 'Admin Test',
            'email' => ('admin@test.com'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => bcrypt('adminTest'),
        ]);
        $response = $this->withHeaders([
            'Authorization' => $this->typeAuthorization . $this->token
        ])->getJson('/api/users');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'email',
                'role',
                'created_at',
                'updated_at',
                'favorites'
            ]
        ]);
        $response->assertJson(fn (AssertableJson $json) => $json->has(3));
    }

    public function test_user_delete()
    {
        $user = User::create([
            'name' => 'Angel Test',
            'email' => ('angel@test.com'),
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => bcrypt('angelTest'),
        ]);

        $response = $this->withHeaders([
            'Authorization' => $this->typeAuthorization . $this->token
        ])->deleteJson("/api/user/$user->id");

        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);
        $response->assertJsonFragment(['message' => 'User successfully deleted.']);
    }
}
