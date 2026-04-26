<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class PageController extends Controller
{
    public function show($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->firstOrFail();

        $data = ['page' => $page];

        // Загружаем данные для страницы "О нас"
        if ($slug === 'about') {
            $data['vehicles'] = \App\Models\Vehicle::where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $data['drivers'] = \App\Models\Driver::where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $data['reviews'] = \App\Models\Review::where('is_published', true)
                ->latest()
                ->get();
        }

        // Загружаем данные для страницы "Контакты"
        // Загружаем данные для страницы "Контакты"
        if ($slug === 'contacts') {
            $data['contacts'] = \App\Models\Contact::with('links')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            return view('contacts', $data); // ВНИМАНИЕ: Используем contacts.blade.php
        }

        return view('page', $data);
    }
}
