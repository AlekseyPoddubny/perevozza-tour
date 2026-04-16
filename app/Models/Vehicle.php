<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'make_model',
        'image',
        'license_plate',
        'seats',
        'description',
        'is_active',
        'sort_order'
    ];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}
