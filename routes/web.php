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
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/dashboard-action', [AuthController::class, 'index']);;
    Route::post('/client', [ClientController::class, 'index']);
    Route::post('/klien', [ClientController::class, 'index'])->name('klien.index');
});

Route::get('/dashboard', function () {
    return view('langganan.dashboard');
})->name('dashboard');



Route::get('/klien', function () {
    return view('langganan.klien');
})->name('klien');

Route::get('/analitik', function () {
    return view('langganan.analitik');
})->name('analitik');

Route::get('/help', function () {
    return view('langganan.help');
})->name('help');
