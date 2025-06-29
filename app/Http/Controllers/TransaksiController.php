<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Pengguna; // Pastikan ini di-import

class TransaksiController extends Controller
{
    public function ajukan($id, Request $request)
    {
        // Pastikan pengguna sudah login dan rolenya 'pengguna'
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            return redirect('/login')->with('error', 'Silakan login sebagai pengguna untuk mengajukan pesanan.');
        }

        // Ambil objek Pengguna yang sedang login dari sesi.
        // Asumsi: AuthController menyimpan objek Pengguna langsung di session('user').
        $loggedInPengguna = Session::get('user');

        // Lakukan validasi ekstra untuk memastikan ini adalah objek Pengguna yang valid
        if (!($loggedInPengguna instanceof Pengguna) || !$loggedInPengguna->id) {
            // Fallback: Jika session 'user' ternyata objek Akun, coba cari Pengguna berdasarkan id_akun
            if ($loggedInPengguna instanceof \App\Models\Akun) {
                 $loggedInPengguna = Pengguna::where('id_akun', $loggedInPengguna->id)->first();
            }

            // Jika masih bukan objek Pengguna yang valid atau ID-nya kosong, arahkan kembali
            if (!$loggedInPengguna || !$loggedInPengguna->id) {
                 return redirect('/login')->with('error', 'Sesi pengguna tidak valid atau data tidak lengkap. Silakan login kembali.');
            }
        }
        $idPembeli = $loggedInPengguna->id; // Ini adalah ID yang benar dari tabel `pengguna`

        // Temukan barang berdasarkan ID
        $barang = Barang::findOrFail($id);

        // Pencegahan: Pengguna tidak bisa memesan barangnya sendiri
        if ($barang->id_pengguna == $idPembeli) {
            return back()->with('error', 'Anda tidak dapat mengajukan pesanan untuk barang Anda sendiri.');
        }

        // Cek status barang
        if ($barang->status !== 'tersedia') {
            return redirect()->route('pesananku')->with('error', 'Barang ini tidak tersedia untuk pesanan.');
        }

        // Cek apakah sudah pernah mengajukan transaksi untuk barang ini yang masih aktif
        $sudah = Transaksi::where('id_barang', $id)
            ->where('id_pembeli', $idPembeli)
            ->whereIn('status', ['diajukan', 'diterima']) // Hanya cek transaksi yang masih dalam proses
            ->first();

        if ($sudah) {
            // Jika ada transaksi yang sedang aktif, tampilkan modal konfirmasi
            return redirect()->route('barang.detail', $barang->id)->with('konfirmasi_pesanan', true);
        }

        // Buat transaksi baru
        Transaksi::create([
            'id_barang' => $id,
            'id_pembeli' => $idPembeli, // Gunakan ID pengguna yang telah divalidasi
            'status' => 'diajukan',
        ]);

        return redirect()->route('pesananku')->with('success', 'Pengajuan pesanan berhasil dikirim!');
    }

    public function pesananku()
    {
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            return redirect('/login');
        }
        $loggedInPengguna = Session::get('user'); // Pastikan ini Pengguna model
        if (!($loggedInPengguna instanceof Pengguna)) {
            // Fallback just in case
            if ($loggedInPengguna instanceof \App\Models\Akun) {
                $loggedInPengguna = Pengguna::where('id_akun', $loggedInPengguna->id)->first();
            }
            if (!$loggedInPengguna || !$loggedInPengguna->id) {
                return redirect('/login')->with('error', 'Sesi pengguna tidak valid atau data tidak lengkap. Silakan login kembali.');
            }
        }
        $userId = $loggedInPengguna->id; // Gunakan ID pengguna yang telah divalidasi

        $pesanan = Transaksi::with(['barang.foto', 'barang.pengguna'])
            ->where('id_pembeli', $userId)
            ->whereIn('status', ['diajukan', 'diterima', 'selesai'])
            ->orderByDesc('id')
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
        // Ambil ID pengguna pemilik barang dari sesi secara aman
        $loggedInPengguna = Session::get('user');
        if (!($loggedInPengguna instanceof Pengguna)) {
             if ($loggedInPengguna instanceof \App\Models\Akun) {
                $loggedInPengguna = Pengguna::where('id_akun', $loggedInPengguna->id)->first();
            }
            if (!$loggedInPengguna || !$loggedInPengguna->id) {
                return back()->with('error', 'Sesi pengguna tidak valid atau data tidak lengkap. Silakan login kembali.');
            }
        }
        $idPenggunaLoggedIn = $loggedInPengguna->id;

        if ($barang->id_pengguna != $idPenggunaLoggedIn) {
            return back()->with('error', 'Akses ditolak. Anda bukan pemilik barang ini.');
        }
        $trx->status = 'diterima';
        $trx->save();
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
        // Ambil ID pengguna pemilik barang dari sesi secara aman
        $loggedInPengguna = Session::get('user');
        if (!($loggedInPengguna instanceof Pengguna)) {
             if ($loggedInPengguna instanceof \App\Models\Akun) {
                $loggedInPengguna = Pengguna::where('id_akun', $loggedInPengguna->id)->first();
            }
            if (!$loggedInPengguna || !$loggedInPengguna->id) {
                return back()->with('error', 'Sesi pengguna tidak valid atau data tidak lengkap. Silakan login kembali.');
            }
        }
        $idPenggunaLoggedIn = $loggedInPengguna->id;

        if ($barang->id_pengguna != $idPenggunaLoggedIn) {
            return back()->with('error', 'Akses ditolak. Anda bukan pemilik barang ini.');
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
        // Ambil ID pengguna pemilik barang dari sesi secara aman
        $loggedInPengguna = Session::get('user');
        if (!($loggedInPengguna instanceof Pengguna)) {
             if ($loggedInPengguna instanceof \App\Models\Akun) {
                $loggedInPengguna = Pengguna::where('id_akun', $loggedInPengguna->id)->first();
            }
            if (!$loggedInPengguna || !$loggedInPengguna->id) {
                return back()->with('error', 'Sesi pengguna tidak valid atau data tidak lengkap. Silakan login kembali.');
            }
        }
        $idPenggunaLoggedIn = $loggedInPengguna->id;

        if ($barang->id_pengguna != $idPenggunaLoggedIn) {
            return back()->with('error', 'Akses ditolak. Anda bukan pemilik barang ini.');
        }
        $barang->status = 'habis';
        $barang->save();
        // Batalkan semua transaksi yang belum selesai untuk barang ini
        $barang->transaksi()->whereNotIn('status', ['selesai'])->update(['status' => 'ditolak']);
        return back()->with('success', 'Barang ditandai habis. Semua transaksi lain dibatalkan.');
    }

    public function tolak($id)
    {
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            return redirect('/login');
        }
        $trx = Transaksi::findOrFail($id);
        $barang = $trx->barang;
        // Pastikan hanya pemilik barang yang bisa menolak
        // Ambil ID pengguna pemilik barang dari sesi secara aman
        $loggedInPengguna = Session::get('user');
        if (!($loggedInPengguna instanceof Pengguna)) {
             if ($loggedInPengguna instanceof \App\Models\Akun) {
                $loggedInPengguna = Pengguna::where('id_akun', $loggedInPengguna->id)->first();
            }
            if (!$loggedInPengguna || !$loggedInPengguna->id) {
                return back()->with('error', 'Sesi pengguna tidak valid atau data tidak lengkap. Silakan login kembali.');
            }
        }
        $idPenggunaLoggedIn = $loggedInPengguna->id;

        if ($barang->id_pengguna != $idPenggunaLoggedIn) {
            return back()->with('error', 'Akses ditolak. Anda bukan pemilik barang ini.');
        }
        $trx->status = 'ditolak';
        $trx->save();
        return back()->with('success', 'Transaksi ditolak.');
    }

    public function hapus($id)
    {
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            return redirect('/login');
        }
        $trx = Transaksi::findOrFail($id);
        // Pastikan hanya pembeli yang bisa menghapus riwayat pesanannya
        // Ambil ID pengguna pembeli dari sesi secara aman
        $loggedInPengguna = Session::get('user');
        if (!($loggedInPengguna instanceof Pengguna)) {
             if ($loggedInPengguna instanceof \App\Models\Akun) {
                $loggedInPengguna = Pengguna::where('id_akun', $loggedInPengguna->id)->first();
            }
            if (!$loggedInPengguna || !$loggedInPengguna->id) {
                return back()->with('error', 'Sesi pengguna tidak valid atau data tidak lengkap. Silakan login kembali.');
            }
        }
        $idPenggunaLoggedIn = $loggedInPengguna->id;

        if ($trx->id_pembeli != $idPenggunaLoggedIn) {
            return back()->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus transaksi ini.');
        }
        $trx->delete();
        return back()->with('success', 'Riwayat pesanan berhasil dihapus.');
    }
}