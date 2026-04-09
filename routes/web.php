<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;

// ================= GUEST =================
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ================= AUTH =================
Route::middleware('auth')->group(function() {

    // 🔥 FIX: pakai controller
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 🔥 FIX: klien pakai controller
    Route::get('/klien', [ClientController::class, 'index'])->name('klien.index');
    Route::post('/klien', [ClientController::class, 'store'])->name('klien.store');
    Route::post('/klien/update', [ClientController::class, 'update'])->name('klien.update');

    // halaman lain boleh tetap view
    Route::get('/analitik', function () {
        return view('langganan.analitik');
    });

    Route::get('/help', function () {
        return view('langganan.help');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/api/dashboard', [DashboardController::class, 'api']);