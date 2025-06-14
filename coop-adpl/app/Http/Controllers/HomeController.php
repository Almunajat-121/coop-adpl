<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['foto', 'kategori'])
            ->where('status', 'tersedia');
        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        $barang = $query->orderByDesc('id')->get();
        $kategori = Kategori::all();
        return view('home', compact('barang', 'kategori'));
    }
}
