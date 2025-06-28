<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Digunakan untuk Session::get('user')
use App\Models\Barang;   // Model Barang Anda
use App\Models\Kategori; // Model Kategori Anda
use App\Models\Foto;     // Model Foto Anda
use App\Models\Laporan;  // Model Laporan Anda
use App\Models\Pengguna; // Model Pengguna Anda

class ProductController extends Controller
{
    /**
     * Mengambil daftar produk (barang) untuk API yang dipanggil oleh home.js.
     */
    public function getProducts(Request $request)
    {
        // Eager load relasi foto, kategori, dan pengguna (termasuk akun pengguna)
        $query = Barang::with(['foto', 'kategori', 'pengguna.akun'])
                        ->where('status', 'tersedia'); // Hanya tampilkan barang yang tersedia

        // Filter berdasarkan keyword (nama barang)
        if ($request->filled('keyword')) {
            $query->where('nama', 'like', '%' . $request->keyword . '%');
        }

        // Filter berdasarkan ID kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Filter agar tidak menampilkan barang milik pengguna yang sedang login (opsional)
        if (Session::has('user') && Session::get('role') === 'pengguna') {
            $userIdAkun = Session::get('user')->id; // Ini adalah ID dari tabel 'akun'
            // Kita perlu ID dari tabel 'pengguna' yang berelasi dengan id_akun di session
            $idPenggunaAsli = Pengguna::where('id_akun', $userIdAkun)->value('id');

            if ($idPenggunaAsli) {
                 $query->where('id_pengguna', '!=', $idPenggunaAsli);
            }
        }

        $products = $query->orderByDesc('id')->get(); // Urutkan berdasarkan ID terbaru

        // Format data agar sesuai dengan ekspektasi home.js
        $formattedProducts = $products->map(function($product) {
            // Ambil URL foto pertama atau placeholder jika tidak ada
            $gambarPaths = $product->foto->pluck('url_foto')->toArray();
            $firstImage = count($gambarPaths) > 0 ? $gambarPaths[0] : null;

            return [
                'id'          => $product->id,
                'nama'        => $product->nama,
                'deskripsi'   => $product->deskripsi,
                // Lokasi diambil dari relasi pengguna->alamat (pengguna.alamat)
                'lokasi'      => $product->pengguna->alamat ?? 'Lokasi tidak diketahui', 
                'gambar'      => json_encode($gambarPaths), // home.js mengharapkan ini sebagai JSON string
                'harga'       => $product->tipe === 'jual' ? 'Rp. ' . number_format($product->harga, 0, ',', '.') : 'Gratis',
                'kategori_nama' => $product->kategori->nama ?? '-',
                'id_pengguna' => $product->id_pengguna, // ID pengguna pemilik barang
                // Tambahkan field lain yang mungkin dibutuhkan di frontend, contoh:
                // 'username_penjual' => $product->pengguna->akun->username ?? 'N/A',
            ];
        });

        return response()->json($formattedProducts);
    }

    /**
     * Menyimpan laporan barang.
     */
    public function report(Request $request, $id_barang)
    {
        // Pastikan hanya pengguna yang login yang bisa melapor
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            return response()->json(['message' => 'Anda harus login sebagai pengguna untuk melapor.', 'status' => 'error'], 401);
        }

        $request->validate([
            'alasan' => 'required|string|min:5',
        ]);

        // Mengambil ID akun dari session
        $idAkunPelapor = Session::get('user')->id; 
        // Mencari ID dari tabel 'pengguna' yang berelasi dengan id_akun di session
        $idPelapor = Pengguna::where('id_akun', $idAkunPelapor)->value('id');

        if (!$idPelapor) {
            return response()->json(['message' => 'Pengguna pelapor tidak ditemukan.', 'status' => 'error'], 404);
        }

        Laporan::create([
            'id_barang'  => $id_barang,
            'id_pelapor' => $idPelapor, // Menggunakan ID dari tabel pengguna
            'alasan'     => $request->alasan,
        ]);

        return response()->json(['message' => 'Laporan berhasil dikirim.', 'status' => 'success']);
    }
}