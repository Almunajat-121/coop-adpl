<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Pengguna;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Ulasan;

class ProfilController extends Controller
{
    public function index()
    {
        if (session('role') !== 'pengguna') {
            return redirect('/login');
        }
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $user = Pengguna::with('akun')->find(session('user')->id);
        if (!$user) {
            return redirect('/login')->with('error', 'Data pengguna tidak ditemukan.');
        }
        $barang = Barang::with('foto')->where('id_pengguna', $user->id)->get();
        $barang_count = $barang->count();
        $transaksi_count = Transaksi::where('id_pembeli', $user->id)->count();
        $rating_avg = Ulasan::whereIn('id_transaksi', Transaksi::where('id_pembeli', $user->id)->pluck('id'))->avg('rating');
        return view('profil', [
            'user' => (object) [
                'nama' => $user->akun->nama,
                'no_telepon' => $user->no_telepon,
                'alamat' => $user->alamat,
                'avatar' => null, // bisa diisi url avatar jika ada
            ],
            'barang' => $barang,
            'barang_count' => $barang_count,
            'transaksi_count' => $transaksi_count,
            'rating_avg' => $rating_avg ? number_format($rating_avg, 1) : null,
        ]);
    }
    
    public function jualanku()
    {
        if (session('role') !== 'pengguna') {
            return redirect('/login');
        }
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $user = Pengguna::with('akun')->find(session('user')->id);
        if (!$user) {
            return redirect('/login')->with('error', 'Data pengguna tidak ditemukan.');
        }
        $barang_diajukan = Barang::with(['foto', 'transaksi' => function($q) {
            $q->orderByDesc('id');
        }])
        ->where('id_pengguna', $user->id)
        ->whereHas('transaksi')
        ->get();
        // Urutkan barang: yang punya transaksi status diajukan/diterima di atas
        $barang_diajukan = $barang_diajukan->sortByDesc(function($barang) {
            return $barang->transaksi->first(function($trx) {
                return in_array($trx->status, ['diajukan','diterima']);
            }) ? 1 : 0;
        })->values();
        return view('jualanku', compact('barang_diajukan'));
    }
    
    public function profilPenjual($id)
    {
        $penjual = \App\Models\Pengguna::with('akun')->findOrFail($id);
        $barang = \App\Models\Barang::with('foto')
            ->where('id_pengguna', $id)
            ->orderByDesc('id')
            ->get();
        return view('profil-penjual', compact('penjual', 'barang'));
    }
}
