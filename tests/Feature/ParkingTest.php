<?php

namespace Tests\Feature;

use App\Models\Parking;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParkingTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_parking()
    {

        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        $zone = Zone::first();

        $response = $this->actingAs($user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $vehicle->id,
            'zone_id' => $zone->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data'])
            ->assertJson([
                'data' => [
                    'start_time' => now()->toDateTimeString(),
                    'stop_time' => null,
                    'total_price' => 0,
                ],
            ]);

        $this->assertDatabaseCount('parkings', '1');
    }

    public function test_stop_parking()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        $zone = Zone::first();

        $this->actingAs($user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $vehicle->id,
            'zone_id' => $zone->id,
        ]);

        $this->travel(2)->hours();

        $parking = Parking::first();
        $response = $this->actingAs($user)->putJson('/api/v1/parkings/'.$parking->id);

        $updatedParking = Parking::find($parking->id);

        $response->assertStatus(202)
            ->assertJsonStructure(['data'])
            ->assertJson([
                'data' => [
                    'start_time' => $updatedParking->start_time->toDateTimeString(),
                    'stop_time' => $updatedParking->stop_time->toDateTimeString(),
                    'total_price' => $updatedParking->total_price,
                ],
            ]);

        $this->assertDatabaseCount('parkings', '1');
    }

    public function test_parking_validation()
    {
        $user = User::factory()->create();

        $zone = Zone::first();

        $response = $this->actingAs($user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => 1,
            'zone_id' => $zone->id,
        ]);

        $response->assertStatus(422);

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        //start parking twice
        $parking = Parking::factory()->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id, 'zone_id' => $zone->id]);

        $response = $this->actingAs($user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $vehicle->id,
            'zone_id' => $zone->id,
        ]);

        $response->assertStatus(422);

        //stop parking twice
        $this->travel(2)->hours();

        $parking->update(['stop_time' => now()]);

        $response = $this->actingAs($user)->putJson('/api/v1/parkings/'.$parking->id, [
            'stop_time' => \Carbon\Carbon::now()->addHour(),
        ]);

        $response->assertStatus(422);
    }

    public function testUserCanGetOngoingParkingWithCorrectPrice()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        $zone = Zone::first();

        $this->actingAs($user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $vehicle->id,
            'zone_id' => $zone->id,
        ]);

        $this->travel(2)->hours();

        $parking = Parking::first();

        $response = $this->actingAs($user)->getJson('/api/v1/parkings/'.$parking->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJson([
                'data' => [
                    'stop_time' => null,
                    'total_price' => $zone->price_per_hour * 2,
                ],
            ]);
    }
}
