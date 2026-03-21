<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('langganan.login');
});

Route::get('/dashboard', function () {
    return view('langganan.dashboard');
})->name('dashboard');

Route::get('/klien', function () {
    return view('langganan.klien');
})->name('klien');