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
            'status' => 'terkirim', // gunakan enum yang valid
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
            ->whereIn('status', ['diajukan', 'diterima', 'selesai']) // tampilkan juga yang sudah diterima dan selesai
            ->orderByDesc('id') // urutkan berdasarkan id, bukan created_at
            ->get();
        return view('pesananku', compact('pesanan'));
    }

    public function terima($id)
    {
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            return redirect('/login');
        }
        $trx = Transaksi::findOrFail($id);
        $barang = $trx->barang;
        // Pastikan hanya pemilik barang yang bisa menerima
        if ($barang->id_pengguna != Session::get('user')->id) {
            return back()->with('error', 'Akses ditolak.');
        }
        $trx->status = 'diterima';
        $trx->save();
        // Tidak mengubah status barang ke 'habis' di sini
        return back()->with('success', 'Transaksi diterima.');
    }

    public function selesai($id)
    {
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            return redirect('/login');
        }
        $trx = Transaksi::findOrFail($id);
        $barang = $trx->barang;
        // Pastikan hanya pemilik barang yang bisa menyelesaikan
        if ($barang->id_pengguna != Session::get('user')->id) {
            return back()->with('error', 'Akses ditolak.');
        }
        $trx->status = 'selesai';
        $trx->save();
        return back()->with('success', 'Transaksi selesai. Pembeli bisa memberi rating.');
    }

    public function habis($id)
    {
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            return redirect('/login');
        }
        $barang = Barang::findOrFail($id);
        if ($barang->id_pengguna != Session::get('user')->id) {
            return back()->with('error', 'Akses ditolak.');
        }
        $barang->status = 'habis';
        $barang->save();
        // Batalkan semua transaksi yang belum selesai
        $barang->transaksi()->whereNotIn('status', ['selesai'])->update(['status' => 'ditolak']);
        return back()->with('success', 'Barang ditandai habis. Semua transaksi lain dibatalkan.');
    }
}
