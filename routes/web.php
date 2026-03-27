<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware()->group(function() {
    Route::post('/Logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/dashboard', [AuthController::class, 'index'])->name('dashboard');
    Route::post('/client', ClientController::class);
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
