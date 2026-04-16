<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class PublicRouteController extends Controller
{
    public function index(\Illuminate\Http\Request $request) // Обязательно добавь Request $request
    {
        // Начинаем строить запрос
        $query = \App\Models\Schedule::with(['route', 'vehicle'])
            ->where('departure_at', '>=', now())
            ->where('status', 'scheduled'); // Показываем только запланированные рейсы

        // Если заполнено поле "Откуда"
        if ($request->filled('from')) {
            $query->whereHas('route', function($q) use ($request) {
                $q->where('departure_city', 'like', '%' . $request->from . '%');
            });
        }

        // Если заполнено поле "Куда"
        if ($request->filled('to')) {
            $query->whereHas('route', function($q) use ($request) {
                $q->where('arrival_city', 'like', '%' . $request->to . '%');
            });
        }

        // Если выбрана дата
        if ($request->filled('date')) {
            $query->whereDate('departure_at', $request->date);
        }

        // Получаем отфильтрованные данные
        $schedules = $query->orderBy('departure_at')->get();

        return view('welcome', compact('schedules'));
    }
}
