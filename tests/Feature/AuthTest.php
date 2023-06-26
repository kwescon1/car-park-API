<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user(): void
    {

        $data = ['email' => 'foo@bar.com', 'password' => 'password', 'password_confirmation' => 'password', 'name' => 'kwesi'];

        $response = $this->postJson('api/v1/auth/register', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure(['data']);
    }

    public function test_register_user_with_valition_error()
    {
        $user = User::factory()->make([]);

        $response = $this->postJson('api/v1/auth/register', $user->toArray());

        $response->assertStatus(422);

        $user = User::factory()->create();

        $response = $this->postJson('api/v1/auth/register', $user->toArray());

        $response->assertStatus(422);
    }

    public function test_login_user_with_right_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);

    }

    public function test_login_user_with_wrong_credentials()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nonexisting@user.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);

    }

    public function test_logout_user()
    {
        $response = $this->postJson('/api/v1/auth/logout');

        //unauthenticated user
        $response->assertStatus(401);

        //authenticated user
        $user = User::factory()->create();

        Sanctum::actingAs($user
        );

        $response = $this->postJson('/api/v1/auth/logout');

        $response->assertStatus(204);
    }
}
