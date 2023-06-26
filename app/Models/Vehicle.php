<?php

namespace App\Models;

use App\Models\Scopes\UserVehicleScope;
use App\Observers\VehicleObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected static function booted()
    {
        self::observe(VehicleObserver::class);

        static::addGlobalScope(new UserVehicleScope);

    }
}
