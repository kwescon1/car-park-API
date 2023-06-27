<?php

namespace App\Services\ParkingService;

use App\Models\Parking;
use App\Models\Zone;
use Carbon\Carbon;

class ParkingService implements ParkingServiceInterface
{
    public function verifyParkingExists(int $vehicleId): bool
    {
        return Parking::active()->whereVehicleId($vehicleId)->exists();
    }

    public function verifyParkingStopped(int $vehicleId): bool
    {
        return Parking::stopped()->whereVehicleId($vehicleId)->exists();
    }

    public static function calculatePrice(int $zone_id, string $startTime, string $stopTime = null): int
    {
        $start = new Carbon($startTime);

        $stop = (! is_null($stopTime)) ? new Carbon($stopTime) : now();

        $totalTimeByMinutes = $stop->diffInMinutes($start);

        $priceByMinutes = Zone::find($zone_id)->price_per_hour / 60;

        return ceil($totalTimeByMinutes * $priceByMinutes);
    }
}
