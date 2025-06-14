<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Transaksi;
use App\Models\Barang;

class TransaksiController extends Controller
{
    public function ajukan($id, Request $request)
    {
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            return redirect('/login');
        }
        $barang = Barang::findOrFail($id);
        if ($barang->status !== 'tersedia') {
            return back()->with('error', 'Barang sudah tidak tersedia.');
        }
        // Cek apakah sudah pernah mengajukan transaksi untuk barang ini
        $sudah = Transaksi::where('id_barang', $id)
            ->where('id_pembeli', Session::get('user')->id)
            ->first();
        if ($sudah) {
            return back()->with('error', 'Anda sudah pernah mengajukan transaksi untuk barang ini.');
        }
        Transaksi::create([
            'id_barang' => $id,
            'id_pembeli' => Session::get('user')->id,
            'status' => 'diajukan',
        ]);
        return back()->with('success', 'Pengajuan transaksi berhasil! Silakan tunggu konfirmasi penjual.');
    }

    public function pesananku()
    {
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            return redirect('/login');
        }
        $userId = Session::get('user')->id;
        $pesanan = Transaksi::with(['barang.foto'])
            ->where('id_pembeli', $userId)
            ->where('status', 'diajukan')
            ->orderByDesc('id') // urutkan berdasarkan id, bukan created_at
            ->get();
        return view('pesananku', compact('pesanan'));
    }
}
