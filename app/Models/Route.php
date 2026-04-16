<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = ['description', 'is_active'];

    public function cities()
    {
        return $this->belongsToMany(City::class, 'city_route')
            ->withPivot('order')
            ->withTimestamps();
    }

    // Для работы админки напрямую с промежуточной таблицей
    public function cityRoute()
    {
        // Указываем класс модели и имя таблицы явно, чтобы не было путаницы
        return $this->hasMany(CityRoute::class, 'route_id')->orderBy('order');
    }

    public static $recordTitleAttribute = 'full_path';

    public function getFullPathAttribute(): string
    {
        // Получаем названия городов, отсортированные по порядку в pivot-таблице
        $cities = $this->cities()->orderBy('city_route.order')->pluck('name');

        if ($cities->isEmpty()) {
            return "Маршрут #" . $this->id . " (без городов)";
        }

        return $cities->implode(' — ');
    }

    // Внутри класса модели Route
    protected $appends = ['full_path']; // Чтобы атрибут всегда был доступен

    public static function getRecordTitleAttribute(): string
    {
        return 'full_path'; // Указываем Filament использовать это поле как заголовок
    }



}
