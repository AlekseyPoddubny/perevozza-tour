<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['schedule_id', 'client_name', 'client_phone', 'passengers_count', 'status'];

    public function schedule() {
        return $this->belongsTo(Schedule::class);
    }
}
