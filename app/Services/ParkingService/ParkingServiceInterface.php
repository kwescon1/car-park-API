<?php

namespace App\Services\ParkingService;

interface ParkingServiceInterface
{
    public function verifyParkingExists(int $vehicleId): bool;

    public function verifyParkingStopped(int $vehicleId): bool;

    public static function calculatePrice(int $zone_id, string $startTime, string $stopTime = null): int;
}
