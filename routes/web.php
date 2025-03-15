<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('kelas', KelasController::class);
