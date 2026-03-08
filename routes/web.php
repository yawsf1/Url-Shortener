<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'authUrls' => Auth::user() ? Auth::user()->urls : [],
    ]);
})->name('home');

Route::middleware('guest')->get('/register', function () {
    return Inertia::render('Register');
})->name('registerPage');

Route::middleware('guest')->get('/login', function () {
    return Inertia::render('Login');
})->name('loginPage');

Route::post('/auth/register', [AuthController::class, 'register'])->name('register')->middleware('throttle:5,1');
Route::post('/auth/login', [AuthController::class, 'login'])->name('login')->middleware('throttle:5,1');

Route::middleware('auth')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/shorten', [UrlController::class, 'shorten'])->name('shorten')->middleware('throttle:30,1');
    Route::delete('/shorten/{url}', [UrlController::class, 'destroy'])->name('shorten.destroy')->middleware('throttle:30,1');
});


Route::get('/{shortUrl}', [UrlController::class, 'redirect'])->name('redirect')->middleware('throttle:100,1');