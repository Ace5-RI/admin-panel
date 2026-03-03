<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanggananController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/Langganan', LanggananController::class);