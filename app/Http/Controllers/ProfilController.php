<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session; // Pastikan ini di-import
use App\Models\Pengguna;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Ulasan;
use App\Models\Akun; // Tambahkan ini jika belum ada

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

        $userAkun = Session::get('user'); // Ini adalah objek Akun dari session
        // Cari objek Pengguna berdasarkan id_akun dari session
        $user = Pengguna::with('akun')->where('id_akun', $userAkun->id)->first();

        if (!$user) {
            // Jika data pengguna tidak ditemukan di tabel 'pengguna'
            return redirect('/login')->with('error', 'Data pengguna tidak ditemukan.');
        }

        $barang = Barang::with('foto')->where('id_pengguna', $user->id)->get();
        $barang_count = $barang->count();

        $transaksi_count = Transaksi::where('id_pembeli', $user->id)->count();

        $rating_avg = Ulasan::whereIn('id_transaksi', Transaksi::where('id_pembeli', $user->id)->pluck('id'))->avg('rating');

        return view('profil', [
            'user' => (object) [ // Buat objek user agar konsisten dengan panggilan di Blade
                'nama' => $user->akun->nama,
                'no_telepon' => $user->no_telepon,
                'alamat' => $user->alamat,
                'id' => $user->id, // Penting: tambahkan ID pengguna dari tabel pengguna
                // 'avatar' => $user->avatar_url_jika_ada, // bisa diisi url avatar jika ada
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
        $userAkun = Session::get('user');
        $user = Pengguna::with('akun')->where('id_akun', $userAkun->id)->first();
        if (!$user) {
            return redirect('/login')->with('error', 'Data pengguna tidak ditemukan.');
        }
        $barang_diajukan = Barang::with(['foto', 'transaksi' => function($q) {
            $q->orderByDesc('id');
        }])
        ->where('id_pengguna', $user->id)
        ->whereHas('transaksi') // Pastikan ada transaksi terkait
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
        $penjual = Pengguna::with('akun')->findOrFail($id);
        $barang = Barang::with('foto')
            ->where('id_pengguna', $id)
            ->orderByDesc('id')
            ->get();
        return view('profil-penjual', compact('penjual', 'barang'));
    }
}