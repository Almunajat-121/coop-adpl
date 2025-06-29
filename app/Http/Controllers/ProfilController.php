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
            return redirect('/login')->with('error', 'Silakan login sebagai pengguna.');
        }
        if (!session('user')) {
            return redirect('/login')->with('error', 'Sesi login tidak ditemukan. Silakan login terlebih dahulu.');
        }

        // Ambil objek Pengguna langsung dari sesi
        // Berdasarkan AuthController, Session::get('user') seharusnya adalah objek Pengguna
        $user = Session::get('user');

        // Pastikan objek yang diambil dari sesi adalah instance dari Pengguna
        // Ini penting jika ada kemungkinan objek lain tersimpan di session
        if (!($user instanceof Pengguna)) {
            // Jika bukan objek Pengguna, bisa jadi ada inkonsistensi sesi.
            // Coba cari Pengguna berdasarkan ID Akun jika session('user') adalah Akun
            if ($user instanceof \App\Models\Akun) {
                $user = Pengguna::with('akun')->where('id_akun', $user->id)->first();
            } else {
                // Jika bukan Pengguna atau Akun, sesi rusak, arahkan ke login.
                return redirect('/login')->with('error', 'Data sesi pengguna tidak valid. Silakan login kembali.');
            }
        }

        // Jika Pengguna tidak ditemukan (misalnya, data di DB sudah dihapus setelah login)
        if (!$user || !$user->akun) { // Pastikan $user dan $user->akun ada
            Session::flush(); // Bersihkan sesi yang rusak
            return redirect('/login')->with('error', 'Data pengguna tidak ditemukan atau tidak lengkap. Silakan login kembali.');
        }

        // Ambil data terkait
        $barang = Barang::with('foto')->where('id_pengguna', $user->id)->get();
        $barang_count = $barang->count();

        $transaksi_count = Transaksi::where('id_pembeli', $user->id)->count();

        // Hitung rata-rata rating
        $ulasanIds = Transaksi::where('id_pembeli', $user->id)
                                ->where('status', 'selesai') // Hanya ulasan dari transaksi selesai
                                ->pluck('id');
        $rating_avg = Ulasan::whereIn('id_transaksi', $ulasanIds)->avg('rating');

        return view('profil', [
            'user' => (object) [
                'nama' => $user->akun->nama,
                'no_telepon' => $user->no_telepon,
                'alamat' => $user->alamat,
                'id' => $user->id,
                // Anda bisa menambahkan 'avatar' di sini jika ada kolom avatar di tabel pengguna
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
        // Ambil objek Pengguna langsung dari sesi
        $user = Session::get('user');
        if (!($user instanceof Pengguna) || !$user->id || !$user->akun) { // Periksa juga user->akun
            return redirect('/login')->with('error', 'Data pengguna tidak valid atau tidak lengkap. Silakan login kembali.');
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