<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_has_no_access()
    {
        $user = User::factory()->make(['email' => 'email@example.com'])->toArray();

        $response = $this->putJson('/api/v1/auth/profile', $user);

        $response->assertStatus(401);

    }

    public function test_update_user_profile()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $userUpdateDetails = ['name' => $user->name, 'email' => 'email@example.com'];

        $response = $this->putJson('/api/v1/auth/profile', $userUpdateDetails);

        $response->assertStatus(200);

        $response->assertJsonFragment(['email' => $userUpdateDetails['email']]);

    }

    public function test_update_user_email_must_be_unique_valaidation_error()
    {

        User::factory()->count(2)->create();

        $user1 = User::find(1);

        $user2 = User::find(2);

        Sanctum::actingAs($user1);

        $userUpdateDetails = ['name' => $user1->name, 'email' => $user2->email];

        $response = $this->putJson('/api/v1/auth/profile', $userUpdateDetails);

        $response->assertStatus(422);
    }

    public function test_update_password()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $userUpdateDetails = ['current_password' => 'password', 'password' => 'email@example.com', 'password_confirmation' => 'email@example.com'];

        $response = $this->putJson('/api/v1/auth/password', $userUpdateDetails);

        $response->assertStatus(204);

    }

    public function test_update_password_error()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $userUpdateDetails = ['current_password' => 'passwords', 'password' => 'email@example.com', 'password_confirmation' => 'email@example.com'];

        $response = $this->putJson('/api/v1/auth/password', $userUpdateDetails);

        $response->assertStatus(422);

    }
}
