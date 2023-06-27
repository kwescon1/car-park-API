<?php

namespace App\Http\Resources;

use App\Services\ParkingService\ParkingService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalPrice = $this->total_price ?? ParkingService::calculatePrice(
            $this->zone_id,
            $this->start_time,
            $this->stop_time
        );

        return [
            'id' => $this->id,
            'zone' => $this->whenLoaded('zone', function () {
                return [
                    'name' => $this->zone->name,
                    'price_per_hour' => $this->zone->price_per_hour,
                ];
            }),
            'vehicle' => $this->whenLoaded('vehicle', function () {
                return [
                    'plate_number' => $this->vehicle->plate_number,
                ];
            }),
            'start_time' => $this->start_time->toDateTimeString(),
            'stop_time' => $this->stop_time?->toDateTimeString(),
            'total_price' => $totalPrice,
        ];
    }
}
