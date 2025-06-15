<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Pengguna;

class BarangDetailController extends Controller
{
    public function show($id)
    {
        $barang = Barang::with(['foto', 'kategori', 'transaksi.ulasan' => function($q) {
            $q->orderByDesc('id');
        }])->findOrFail($id);
        $penjual = Pengguna::with('akun')->findOrFail($barang->id_pengguna);
        // Ambil semua ulasan dari transaksi yang sudah selesai dan ada ulasan
        $ulasan = collect();
        foreach ($barang->transaksi as $trx) {
            if ($trx->status == 'selesai' && $trx->ulasan) {
                $ulasan->push($trx->ulasan);
            }
        }
        return view('barang-detail', compact('barang', 'penjual', 'ulasan'));
    }
    // Placeholder untuk aksi lapor dan ajukan pesanan
    public function lapor($id) { return back()->with('success', 'Laporan terkirim!'); }
    public function ajukan($id) { return back()->with('success', 'Pengajuan pesanan terkirim!'); }
}
