<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\InvoiceController;
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
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/klien', [ClientController::class, 'index'])->name('klien.index');
    Route::post('/klien', [ClientController::class, 'store'])->name('klien.store');
    Route::post('/klien/{id}', [ClientController::class, 'update'])->name('klien.update');
    Route::delete('klien/{id}', [ClientController::class, 'destroy'])->name('klien.destroy');

    Route::get('/analitik', function () {
        return view('langganan.analitik');
    });

    Route::get('/aktivitas', function () {
        return view('langganan.aktivitas');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // ← cukup satu
});

// ================= API ROUTES (Dashboard) =================


// Biarkan yang ini saja (sudah benar)
Route::prefix('api/dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'api']);
    Route::get('/total-klien', [DashboardController::class, 'getTotalKlienDetail']);
    Route::get('/klien-aktif', [DashboardController::class, 'getKlienAktifDetail']);
    Route::get('/klien-tidak-aktif', [DashboardController::class, 'getKlienTidakAktifDetail']);
    Route::get('/klien-akan-berakhir', [DashboardController::class, 'getKlienAkanBerakhirDetail']);
    Route::get('/total-pendapatan', [DashboardController::class, 'getTotalPendapatanDetail']);
<<<<<<< HEAD
});

// ================= PAYMENT ROUTE =================
Route::middleware('auth')->group(function (){
    Route::get('payment/{client_id}',[PaymentController::class,'show'])->name('payment.page');
    Route::post();
    Route::get();
});
=======
    Route::get('/tahun-pendapatan', [DashboardController::class, 'getTahunPendapatan']); 
});



Route::get('/aktivitas', [ActivityController::class, 'index']);
Route::get('/api/activities', [ActivityController::class, 'getActivities']);
Route::post('/invoice/generate/{id}', [InvoiceController::class, 'generateAndSend']);
>>>>>>> 25d930959d8f54cec523aafbb73fc773177c6ddc
