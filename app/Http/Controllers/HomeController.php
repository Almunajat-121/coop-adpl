<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Karena ini adalah landing page, kita tidak perlu query barang di sini.
        // Cukup kembalikan view untuk landing page.
        return view('beranda');
    }

    // Opsional: Jika Anda tetap ingin ada halaman terpisah untuk menampilkan barang,
    // Anda bisa membuat fungsi baru, misalnya 'showBarang', dan atur rutenya di web.php
    public function showBarang(Request $request)
    {
        $query = Barang::with(['foto', 'kategori'])
            ->where('status', 'tersedia');
        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        // Filter agar tidak menampilkan barang milik sendiri
        if (session('user')) {
            $query->where('id_pengguna', '!=', session('user')->id);
        }
        $barang = $query->orderByDesc('id')->get();
        $kategori = Kategori::all();
        return view('home', compact('barang', 'kategori')); // Tetap mengembalikan view 'home' yang sudah ada untuk daftar barang
    }
}