<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    private $typeAuthorization = 'Bearer ';
    private $header = ['Accept' => 'application/json'];

    private $routeLogin = '/api/login';
    private $userEmail = 'admin@admin.com';
    private $userPass = 'admin';
    private $token = '';
    private $user;

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

        $this->user = User::where('email', $this->userEmail)->get()->first();

        Favorite::create([
            'quote' => 'Prueba Quote1',
            'user_id' => $this->user->id
        ]);
        Favorite::create([
            'quote' => 'Prueba Quote2',
            'user_id' => $this->user->id
        ]);
        Favorite::create([
            'quote' => 'Prueba Quote3',
            'user_id' => $this->user->id
        ]);
    }

    public function test_favorites()
    {
        $response = $this->withHeaders([
            'Authorization' => $this->typeAuthorization . $this->token
        ])->getJson('/api/favorites');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                "id",
                "quote",
                "created_at",
                "updated_at",
                "user_id",
                "user_name",
                "user" => [
                    "id",
                    "name",
                    "role",
                    "email",
                    "email_verified_at",
                    "created_at",
                    "updated_at",
                ],
            ]
        ]);
        $response->assertJson(fn (AssertableJson $json) => $json->has(3));
    }

    public function test_favorites_save()
    {
        $body = [
            "quote" => 'Quote prueba',
            "user_id" => $this->user->id
        ];
        $response = $this->withHeaders([
            'Authorization' => $this->typeAuthorization . $this->token
        ])->postJson('/api/favorites', $body);

        $response->assertStatus(200);
        $response->assertJsonStructure(['favorite', 'message']);
        $response->assertJsonFragment(['message' => 'Quote has been saved as favorite.']);
        $response->assertJsonFragment([
            "quote" => 'Quote prueba',
            "user_id" => $this->user->id
        ]);
    }

    public function test_favorites_delete()
    {
        $favorite = Favorite::create([
            'quote' => 'Prueba Quote delete',
            'user_id' => $this->user->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => $this->typeAuthorization . $this->token
        ])->deleteJson("/api/favorites/$favorite->id");

        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);
        $response->assertJsonFragment(['message' => 'Quote Kanye West successfully deleted.']);
    }

}
