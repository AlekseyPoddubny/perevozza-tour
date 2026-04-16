<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'route_id',
        'vehicle_id',
        'driver_id',
        'price_rub', // Должно быть тут
        'price_eur', // Должно быть тут
        'status',
        'type',
        'departure_at',
        'frequency',
    ];

    public function route() {
        return $this->belongsTo(Route::class);
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function driver() {
        return $this->belongsTo(Driver::class);
    }

    protected $casts = [
        'price_rub' => 'decimal:2',
        'price_eur' => 'decimal:2',
        'departure_at' => 'datetime',
    ];
}
