<?php

use App\Http\Controllers\PublicRouteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SiteController;

//Route::get('/', [PublicRouteController::class, 'index']);
//Route::post('/book', [BookingController::class, 'store']);

Route::get('/', [SiteController::class, 'index'])->name('home');
Route::get('/search', [SiteController::class, 'index'])->name('search');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

Route::view('/about', 'about')->name('about');

Route::get('/page/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('page.show');
