<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangEditController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BarangDetailController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\UlasanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'dashboardPengguna'])->name('dashboard');
Route::get('/admin/dashboard', [LaporanController::class, 'index'])->name('admin.dashboard');
Route::get('/barang/unggah', [BarangController::class, 'create'])->name('barang.create');
Route::post('/barang/unggah', [BarangController::class, 'store'])->name('barang.store');
Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
Route::get('/barang/{id}/edit', [BarangEditController::class, 'edit'])->name('barang.edit');
Route::put('/barang/{id}', [BarangEditController::class, 'update'])->name('barang.update');
Route::get('/barang/{id}', [BarangDetailController::class, 'show'])->name('barang.detail');
Route::post('/barang/{id}/lapor', [LaporanController::class, 'store'])->name('barang.lapor');
Route::post('/barang/{id}/ajukan', [TransaksiController::class, 'ajukan'])->name('barang.ajukan');
Route::get('/pesananku', [TransaksiController::class, 'pesananku'])->name('pesananku');
Route::delete('/foto/{id}', [FotoController::class, 'destroy'])->name('foto.destroy');
Route::post('/foto/{barang}', [FotoController::class, 'store'])->name('foto.store');
Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.hapus');
Route::post('/laporan/{id}/abaikan', [LaporanController::class, 'abaikan'])->name('laporan.abaikan');
Route::get('/admin/barang/{id}', [LaporanController::class, 'barangDetail'])->name('admin.barang.detail');
Route::delete('/admin/barang/{id}', [LaporanController::class, 'hapusBarang'])->name('admin.barang.hapus');
Route::get('/jualanku', [ProfilController::class, 'jualanku'])->name('jualanku');
Route::post('/transaksi/{id}/terima', [TransaksiController::class, 'terima'])->name('transaksi.terima');
Route::post('/transaksi/{id}/selesai', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');
Route::post('/barang/{id}/habis', [TransaksiController::class, 'habis'])->name('barang.habis');
Route::get('/ulasan/{id}', [UlasanController::class, 'form'])->name('ulasan.form');
Route::post('/ulasan/{id}', [UlasanController::class, 'simpan'])->name('ulasan.simpan');
Route::get('/penjual/{id}', [ProfilController::class, 'profilPenjual'])->name('penjual.profil');
