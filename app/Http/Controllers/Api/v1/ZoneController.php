<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ZoneResource;
use App\Models\Zone;
use Illuminate\Http\Request;

/**
 * @group Zones
 */
class ZoneController extends Controller
{
    /**
     * Get All Zones
     *
     * This endpoint retrieves all the zones available in the system.
     * It returns a collection of Zone resources containing information about each zone.
     * The response will have a 200 OK status code on success.
     *
     * @response 200 {"data": [{"id": 1,"name": "Green Zone","price_per_hour": 100}],"message": null}
     */
    public function index()
    {
        return response()->success(ZoneResource::collection(Zone::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
