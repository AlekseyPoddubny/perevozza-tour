<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        // 1. Собираем все города, которые участвуют в маршрутах
        // Используем модель City, так как теперь города хранятся там
        $citiesFrom = \App\Models\City::whereHas('routes')
            ->orderBy('name')
            ->pluck('name', 'id'); // Получаем имя и ID для выпадающего списка

        $citiesTo = \App\Models\City::whereHas('routes')
            ->orderBy('name')
            ->pluck('name', 'id');

        // Начинаем строить запрос расписания
        $query = \App\Models\Schedule::with(['route.cities', 'vehicle'])
            ->where('status', 'scheduled');

        // Фильтры поиска (если пользователь выбрал города)
        if ($request->filled('from')) {
            $query->whereHas('route.cities', function($q) use ($request) {
                $q->where('cities.id', $request->from);
            });
        }

        if ($request->filled('to')) {
            $query->whereHas('route.cities', function($q) use ($request) {
                $q->where('cities.id', $request->to);
            });
        }

        $schedules = $query->get();

        return view('welcome', compact('citiesFrom', 'citiesTo', 'schedules'));
    }
}
