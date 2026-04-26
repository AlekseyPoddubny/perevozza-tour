<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = ['full_name', 'phone', 'additional_info', 'photo', 'is_active'];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}
