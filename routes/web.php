<?php

use App\Http\Controllers\BulanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisPembayaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
Route::post('/siswa/import', [SiswaController::class, 'proses_import'])->name('siswa.import.store');
Route::get('/siswa/template', [SiswaController::class, 'template'])->name('siswa.template');
Route::resource('siswa', SiswaController::class);

Route::resource('kelas', KelasController::class);

Route::get('/tahun_ajaran', [TahunAjaranController::class, 'index'])->name('tahun_ajaran.index');
Route::get('/tahun_ajaran/create', [TahunAjaranController::class, 'create'])->name('tahun_ajaran.create');
Route::post('/tahun_ajaran', [TahunAjaranController::class, 'store'])->name('tahun_ajaran.store');
Route::get('/tahun_ajaran/{id}/edit', [TahunAjaranController::class, 'edit'])->name('tahun_ajaran.edit');
Route::PUT('/tahun_ajaran/{id}', [TahunAjaranController::class, 'update'])->name('tahun_ajaran.update');
Route::DELETE('/tahun_ajaran/{id}', [TahunAjaranController::class, 'destroy'])->name('tahun_ajaran.destroy');
Route::put('/tahun_ajaran/{id}/active', [TahunAjaranController::class, 'active'])->name('tahun_ajaran.active');

Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
Route::get('/pos/{id}', [PosController::class, 'show'])->name('pos.show');
Route::PUT('/pos/{id}', [PosController::class, 'update'])->name('pos.update');
Route::DELETE('/pos/{id}', [PosController::class, 'destroy'])->name('pos.destroy');

Route::get('/jenis_pembayaran', [JenisPembayaranController::class, 'index'])->name('jenis_pembayaran.index');
Route::get('/jenis_pembayaran/create', [JenisPembayaranController::class, 'create'])->name('jenis_pembayaran.create');
Route::post('/jenis_pembayaran', [JenisPembayaranController::class, 'store'])->name('jenis_pembayaran.store');
Route::get('/jenis_pembayaran/{id}/edit', [JenisPembayaranController::class, 'edit'])->name('jenis_pembayaran.edit');
Route::PUT('/jenis_pembayaran/{id}', [JenisPembayaranController::class, 'update'])->name('jenis_pembayaran.update');
Route::DELETE('/jenis_pembayaran/{id}', [JenisPembayaranController::class, 'destroy'])->name('jenis_pembayaran.destroy');
Route::get('/jenis_pembayaran/{id}/payment_bebas', [JenisPembayaranController::class, 'payment_bebas']);
Route::get('/jenis_pembayaran/{id}/get_payment_bebas', [JenisPembayaranController::class, 'get_payment_bebas']);
Route::get('/jenis_pembayaran/{id}/create_payment_bebas', [JenisPembayaranController::class, 'add_payment_bebas']);
Route::post('/jenis_pembayaran/{id}/payment_bebas', [JenisPembayaranController::class, 'store_payment_bebas']);
Route::DELETE('/jenis_pembayaran/{id}/payment_bebas', [JenisPembayaranController::class, 'destroy_payment_bebas']);
Route::get('/jenis_pembayaran/{id}/payment_bulanan', [JenisPembayaranController::class, 'payment_bulanan']);
Route::get('/jenis_pembayaran/{id}/get_payment_bulanan', [JenisPembayaranController::class, 'get_payment_bulanan']);
Route::get('/jenis_pembayaran/{id}/create_payment_bulanan', [JenisPembayaranController::class, 'add_payment_bulanan']);
Route::get('/jenis_pembayaran/{jenis_pembayaran_id}/payment_bulanan/{siswa_id}', [JenisPembayaranController::class, 'show_payment_bulanan']);
Route::post('/jenis_pembayaran/{id}/payment_bulanan', [JenisPembayaranController::class, 'store_payment_bulanan']);
Route::DELETE('/jenis_pembayaran/{id}/payment_bulanan', [JenisPembayaranController::class, 'destroy_payment_bulanan']);

Route::get('/jenis_pembayaran/{id}/get', [JenisPembayaranController::class, 'get']);

Route::get('/bulan', [BulanController::class, 'index'])->name('bulan.index');
Route::post('/bulan', [BulanController::class, 'store'])->name('bulan.store');
Route::get('/bulan/{id}', [BulanController::class, 'show'])->name('bulan.show');
Route::PUT('/bulan/{id}', [BulanController::class, 'update'])->name('bulan.update');
Route::DELETE('/bulan/{id}', [BulanController::class, 'destroy'])->name('bulan.destroy');

Route::get('/payout', [PayoutController::class, 'index'])->name('payout.index');
Route::PUT('/payout/bulanan/pay/{siswa_id}/{jenis_pembayaran_id}/{bulan_id}', [PayoutController::class, 'bulanan_pay'])->name('payout.bulanan.pay');
Route::PUT('/payout/bulanan/no_pay/{siswa_id}/{jenis_pembayaran_id}/{bulan_id}', [PayoutController::class, 'bulanan_no_pay'])->name('payout.bulanan.no_pay');
