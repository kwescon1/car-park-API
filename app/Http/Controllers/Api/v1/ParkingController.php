<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StartParkingRequest;
use App\Http\Resources\ParkingResource;
use App\Models\Parking;
use App\Services\ParkingService\ParkingServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ParkingController extends Controller
{
    private $parkingService;

    public function __construct(ParkingServiceInterface $parkingService)
    {
        $this->parkingService = $parkingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StartParkingRequest $request): ?JsonResponse
    {

        $parkingData = $request->validated();

        if ($this->parkingService->verifyParkingExists($parkingData['vehicle_id'])) {
            return response()->error(['general' => ['Can\'t start parking twice using same vehicle. Please stop currently active parking.']], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $parking = Parking::create($parkingData);
        $parking->load('vehicle', 'zone');

        return response()->created(ParkingResource::make($parking));
    }

    /**
     * Display the specified resource.
     */
    public function show(Parking $parking)
    {
        return response()->success(ParkingResource::make($parking));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parking $parking)
    {
        if ($this->parkingService->verifyParkingStopped($parking->vehicle_id)) {
            return response()->error(['general' => ['Can\'t stop parking twice']], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $parking->update([
            'stop_time' => now(),
            'total_price' => $this->parkingService::calculatePrice($parking->zone_id, $parking->start_time),
        ]);

        return response()->success(ParkingResource::make($parking), 'Stop time updated', Response::HTTP_ACCEPTED);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
