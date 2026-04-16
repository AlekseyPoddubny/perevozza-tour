<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CityRoute extends Model
{
    // Явно указываем имя таблицы (без буквы 's' на конце)
    protected $table = 'city_route';

    protected $fillable = ['route_id', 'city_id', 'order'];

    // Связь с городом для выпадающего списка в админке
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
