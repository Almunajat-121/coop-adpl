<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Ulasan;
use App\Models\Transaksi;

class UlasanController extends Controller
{
    public function form($id)
    {
        $trx = Transaksi::with('barang')->findOrFail($id);
        // Hanya pembeli yang bisa mengulas dan hanya jika status selesai
        if (Session::get('user')->id != $trx->id_pembeli || $trx->status != 'selesai') {
            return back()->with('error', 'Akses ditolak.');
        }
        $ulasan = Ulasan::where('id_transaksi', $id)->first();
        return view('ulasan-form', compact('trx', 'ulasan'));
    }

    public function simpan(Request $request, $id)
    {
        $trx = Transaksi::findOrFail($id);
        if (Session::get('user')->id != $trx->id_pembeli || $trx->status != 'selesai') {
            return back()->with('error', 'Akses ditolak.');
        }
        $request->validate([
            'isi' => 'required|min:5',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        Ulasan::updateOrCreate(
            ['id_transaksi' => $id],
            ['isi' => $request->isi, 'rating' => $request->rating]
        );
        return redirect()->route('pesananku')->with('success', 'Ulasan berhasil disimpan!');
    }
}
