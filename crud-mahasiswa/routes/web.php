<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;

Route::get('/', function () {
    return redirect('/mahasiswa'); // Biar root langsung ke halaman mahasiswa
});

Route::resource('mahasiswa', MahasiswaController::class);
