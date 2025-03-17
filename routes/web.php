<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunPelajaranController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('siswa', SiswaController::class);
Route::get('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
Route::post('/siswa/import', [SiswaController::class, 'proses_import'])->name('siswa.import.store');
Route::get('/siswa/template', [SiswaController::class, 'template'])->name('siswa.template');

Route::resource('kelas', KelasController::class);

Route::resource('tahun_pelajaran', TahunPelajaranController::class);
Route::put('/tahun_pelajaran/{id}/active', [TahunPelajaranController::class, 'active'])->name('tahun_pelajaran.active');

Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
Route::get('/pos/{id}', [PosController::class, 'show'])->name('pos.show');
Route::PUT('/pos/{id}', [PosController::class, 'update'])->name('pos.update');
Route::DELETE('/pos/{id}', [PosController::class, 'destroy'])->name('pos.destroy');
