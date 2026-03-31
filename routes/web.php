<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;


Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function() {
 Route::get('/dashboard', function () {
    return view('langganan.dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/klien', function () {
    return view('langganan.klien');
})->middleware('auth')->name('klien');

Route::get('/analitik', function () {
    return view('langganan.analitik');
})->middleware('auth')->name('analitik');

Route::get('/help', function () {
    return view('langganan.help');
})->middleware('auth')->name('help');
});

Route::get('/dashboard', function () {
    return view('langganan.dashboard');
})->middleware('auth')->name('dashboard');



Route::get('/klien', function () {
    return view('langganan.klien');
})->name('klien');

Route::get('/analitik', function () {
    return view('langganan.analitik');
})->name('analitik');

Route::get('/help', function () {
    return view('langganan.help');
})->name('help');
