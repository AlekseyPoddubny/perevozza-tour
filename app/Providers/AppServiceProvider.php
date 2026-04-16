<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Contact;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Передаем активные контакты во все вьюхи
        View::composer('*', function ($view) {
            $view->with('headerContacts', Contact::with('links')
                ->where('category', 'personal') // Только диспетчеры
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->take(3) // Ограничим количество для меню
                ->get());
        });
    }
}
