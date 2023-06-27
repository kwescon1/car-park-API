<?php

namespace App\Models;

use App\Models\Scopes\UserParkingScope;
use App\Observers\ParkingObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = ['start_time' => 'datetime', 'stop_time' => 'datetime'];

    protected static function booted()
    {
        self::observe(ParkingObserver::class);

        static::addGlobalScope(new UserParkingScope);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('stop_time')->latest();
    }

    public function scopeStopped($query)
    {
        return $query->whereNotNull('stop_time')->latest();
    }
}
