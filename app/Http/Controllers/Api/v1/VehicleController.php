<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\Response;

/**
 * @group Vehicles
 */
class VehicleController extends Controller
{
    /**
     * Get All User Vehicles
     *
     * @authenticated
     *
     * This endpoint retrieves all the authenticated user's vehicles
     * It returns a collection of vehicles
     * The response will have a 200 OK status code on success.
     *
     * @response 200  "data": [{"id": 1,"plate_number": "1234_AC"},{"id": 2,"plate_number": "1234_AC"}],"message": null}
     */
    public function index()
    {
        return response()->success(VehicleResource::collection(Vehicle::all()));

    }

    /**
     * Create a New Vehicle
     *
     * @authenticated
     *
     * This endpoint allows you to create a new vehicle in the system.
     * Provide the required information in the request body to create the vehicle.
     * On success, it returns the created vehicle resource with a 201 Created status code.
     * If there are validation errors, it returns a 422 Unprocessable Entity status code.
     *
     * @bodyParam plate_number string required The plate number of the vehicle.
     *
     * @response 201 {"data": {"id": 5,"plate_number": "1234_ACs"},"message": null}
     * @response 422 {"message": "The plate number has already been taken.","errors": {"plate_number": ["The plate number has already been taken."]}}
     */
    public function store(StoreVehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());

        return response()->created(VehicleResource::make($vehicle));
    }

    /**
     * Show User Vehicle Details
     *
     * @authenticated
     *
     * This endpoint retrieves the details of the authenticated user's vehicle.
     *
     * @queryParams int $vehicleId The ID of the vehicle
     *
     * @response 200 {"data": {"id": 1,"plate_number": "1234_AC"},"message": null}
     */
    public function show(Vehicle $vehicle)
    {
        return response()->success(VehicleResource::make($vehicle));
    }

    /**
     * Update Vehicle Details
     *
     * @authenticated
     *
     * This endpoint allows you to update an authenticated user's specified vehicle
     *
     * @bodyParam plate_number string required The plate number of the vehicle.
     *
     * @queryParams int $vehicleId The ID of the vehicle
     *
     * @response 202 {"data": {"id": 1,"plate_number": "1234_ACss"},"message": "Vehicle updated successfully"}
     * @response 422 {"message": "The plate number has already been taken.","errors": {"plate_number": ["The plate number has already been taken."]}}
     */
    public function update(StoreVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        return response()->success(VehicleResource::make($vehicle), 'Vehicle updated successfully', Response::HTTP_ACCEPTED);
    }

    /**
     * Delete Vehicle Details
     *
     * @authenticated
     *
     * This endpoint allows you to delete an authenticated user's specified vehicle
     *
     * @queryParams int $vehicleId The ID of the vehicle
     *
     * @response 204
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return response()->noContent();
    }
}
