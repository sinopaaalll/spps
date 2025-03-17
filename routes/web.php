<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunPelajaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
Route::post('/siswa/import', [SiswaController::class, 'proses_import'])->name('siswa.import.store');
Route::get('/siswa/template', [SiswaController::class, 'template'])->name('siswa.template');

Route::resource('kelas', KelasController::class);
Route::resource('tahun_pelajaran', TahunPelajaranController::class);
Route::resource('siswa', SiswaController::class);
