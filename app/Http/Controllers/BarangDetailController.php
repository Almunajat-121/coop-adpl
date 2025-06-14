<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Pengguna;

class BarangDetailController extends Controller
{
    public function show($id)
    {
        $barang = Barang::with(['foto', 'kategori'])->findOrFail($id);
        $penjual = Pengguna::with('akun')->findOrFail($barang->id_pengguna);
        return view('barang-detail', compact('barang', 'penjual'));
    }
    // Placeholder untuk aksi lapor dan ajukan pesanan
    public function lapor($id) { return back()->with('success', 'Laporan terkirim!'); }
    public function ajukan($id) { return back()->with('success', 'Pengajuan pesanan terkirim!'); }
}
