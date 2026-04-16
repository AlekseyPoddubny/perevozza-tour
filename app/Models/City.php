<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    // Обязательно должны быть эти поля:
    protected $fillable = ['name', 'is_main'];

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'city_route')
            ->withPivot('order')
            ->orderByPivot('order');
    }
}
