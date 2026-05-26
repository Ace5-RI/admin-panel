<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;

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

});

// ================= PUBLIC ACCESS FOR INVOICE PDF =================
Route::get('/invoices/{filename}', function ($filename) {
    $path = public_path('invoices/' . $filename);
    
    if (!file_exists($path)) {
        abort(404, 'File not found');
    }
    
    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $filename . '"'
    ]);
})->where('filename', '.*\.pdf$');

// ================= PAYMENT ROUTE =================
Route::middleware('auth')->group(function (){
    Route::get('payment/{client_id}',[PaymentController::class,'show'])->name('payment.page');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('payment/invoice/{client_id/{invoice_id}',[PaymentController::class, 'invoice'])->name('payment.invoice');
});



Route::get('/aktivitas', [ActivityController::class, 'index']);
Route::get('/api/activities', [ActivityController::class, 'getActivities']);
Route::post('/invoice/generate/{id}', [InvoiceController::class, 'generateAndSend']);
Route::get('/invoice/generate/{id}', [InvoiceController::class, 'generateAndSend']);


Route::delete('/api/activities/clear', [ActivityController::class, 'clearAll']);
// Delete single activity
Route::delete('/api/activities/{id}', [ActivityController::class, 'delete']);

// Delete multiple activities
Route::post('/api/activities/delete-multiple', [ActivityController::class, 'deleteMultiple']);