<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class QuoteTest extends TestCase
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
    public function test_quote()
    {
        $response = $this->withHeaders([
            'Authorization' => $this->typeAuthorization . $this->token
        ])->getJson('/api/quote');
        $response->assertStatus(200);
        $response->assertJsonStructure(['quote']);
    }

    public function test_quotes_num()
    {
        $response = $this->withHeaders([
            'Authorization' => $this->typeAuthorization . $this->token
        ])->getJson('/api/quotes/4');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['quote']
        ]);
        $response->assertJson(fn (AssertableJson $json) => $json->has(4));
    }
}
