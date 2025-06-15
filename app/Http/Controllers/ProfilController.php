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
        $user = Pengguna::with('akun')->find(session('user')->id);
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
        $user = Pengguna::with('akun')->find(session('user')->id);
        // Barang milik user yang sedang diajukan transaksi (status transaksi = 'diajukan')
        $barang_diajukan = Barang::with(['foto', 'transaksi' => function($q) {
            $q->where('status', 'diajukan');
        }])->where('id_pengguna', $user->id)
          ->whereHas('transaksi', function($q){ $q->where('status', 'diajukan'); })
          ->get();
        return view('jualanku', compact('barang_diajukan'));
    }
}
