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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/klien', [ClientController::class, 'index'])->name('klien.index');
    Route::post('/klien', [ClientController::class, 'store'])->name('klien.store');
    Route::post('/klien/{id}', [ClientController::class, 'update'])->name('klien.update');
    Route::delete('klien/{id}', [ClientController::class, 'destroy'])->name('klien.destroy');

    Route::get('/analitik', function () {
        return view('langganan.analitik');
    });

    Route::get('/help', function () {
        return view('langganan.help');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ================= API ROUTES (Dashboard) =================
Route::prefix('api/dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'api']);  // /api/dashboard
    Route::get('/total-klien', [DashboardController::class, 'getTotalKlienDetail']);
    Route::get('/klien-aktif', [DashboardController::class, 'getKlienAktifDetail']);
    Route::get('/klien-tidak-aktif', [DashboardController::class, 'getKlienTidakAktifDetail']);
    Route::get('/klien-akan-berakhir', [DashboardController::class, 'getKlienAkanBerakhirDetail']);
    Route::get('/total-pendapatan', [DashboardController::class, 'getTotalPendapatanDetail']);
});