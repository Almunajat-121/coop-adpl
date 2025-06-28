<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;   // Pastikan di-import
use App\Models\Kategori; // Pastikan di-import
use Illuminate\Support\Facades\Session; // <-- Pastikan ini di-import juga!
use App\Models\Pengguna; // <-- Pastikan ini di-import juga!

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ini tetap untuk landing page
        return view('beranda');
    }

    public function showBarang(Request $request)
    {
        // Ambil kategori untuk filter pills di view
        $kategori = Kategori::all(); 

        // Query untuk mendapatkan barang yang akan ditampilkan di halaman 'home'
        $query = Barang::with(['foto', 'kategori'])
            ->where('status', 'tersedia');

        // Filter berdasarkan pencarian (q)
        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Filter agar tidak menampilkan barang milik sendiri (jika pengguna login)
        if (Session::has('user') && Session::get('role') === 'pengguna') {
            $userIdAkun = Session::get('user')->id; // Ini adalah ID dari tabel 'akun'
            // Kita perlu ID dari tabel 'pengguna' yang berelasi dengan id_akun di session
            $idPenggunaAsli = Pengguna::where('id_akun', $userIdAkun)->value('id');

            if ($idPenggunaAsli) {
                $query->where('id_pengguna', '!=', $idPenggunaAsli);
            }
        }

        $barang = $query->orderByDesc('id')->get();

        // Teruskan variabel $barang dan $kategori ke view 'home'
        return view('home', compact('barang', 'kategori'));
    }
}