<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Foto;
use App\Models\Pengguna; // Pastikan ini di-import

class BarangController extends Controller
{
    public function create()
    {
        if (session('role') !== 'pengguna') {
            return redirect('/login');
        }
        $kategori = Kategori::all();
        return view('barang-unggah', compact('kategori'));
    }

    public function store(Request $request)
    {
        // Pastikan pengguna sudah login dan rolenya 'pengguna' untuk semua respons (AJAX/non-AJAX)
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Anda harus login sebagai pengguna.', 'status' => 'error', 'redirect' => route('login')], 401);
            }
            return redirect('/login')->with('error', 'Silakan login sebagai pengguna.');
        }

        $idAkunPengguna = Session::get('user')->id; // Ini adalah ID dari tabel 'akun'
        $pengguna = Pengguna::where('id_akun', $idAkunPengguna)->first();

        if (!$pengguna) {
            // Jika objek Pengguna tidak ditemukan di tabel 'pengguna'
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Data pengguna tidak ditemukan.', 'status' => 'error', 'redirect' => route('login')], 404);
            }
            return redirect('/login')->with('error', 'Data pengguna tidak ditemukan.');
        }

        // Lakukan validasi. Laravel secara otomatis akan mengembalikan JSON (422) untuk AJAX jika validasi gagal.
        $request->validate([
            'nama' => 'required|string',
            'deskripsi' => 'required|string',
            'tipe' => 'required|in:jual,donasi',
            'harga' => 'nullable|numeric',
            'id_kategori' => 'required|exists:kategori,id',
            'foto' => 'required', // Tetap required untuk unggah baru
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'foto.required' => 'Setidaknya satu foto barang harus diunggah.',
            'foto.*.image' => 'File harus berupa gambar.',
            'foto.*.mimes' => 'Format gambar yang diperbolehkan adalah jpeg, png, jpg.',
            'foto.*.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $barang = Barang::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'harga' => $request->tipe === 'jual' ? $request->harga : null,
            'id_pengguna' => $pengguna->id, // Gunakan ID dari model Pengguna
            'id_kategori' => $request->id_kategori,
            'status' => 'tersedia',
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('barang', 'public');
                Foto::create([
                    'id_barang' => $barang->id,
                    'url_foto' => $path,
                ]);
            }
        }

        // Selalu kembalikan respons JSON untuk AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Barang berhasil diunggah!',
                'status' => 'success',
                'redirect' => route('profil') // Redirect setelah sukses
            ], 200);
        }

        // Fallback untuk non-AJAX (jika ada)
        return redirect()->route('profil')->with('success', 'Barang berhasil diunggah!');
    }

    public function update(Request $request, $id)
    {
        // Validasi sesi dan hak akses
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Anda harus login.', 'status' => 'error', 'redirect' => route('login')], 401);
            }
            return redirect('/login');
        }

        $barang = Barang::findOrFail($id);
        $idAkunPengguna = Session::get('user')->id;
        $pengguna = Pengguna::where('id_akun', $idAkunPengguna)->first();
        if (!$pengguna || $barang->id_pengguna != $pengguna->id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Akses ditolak.', 'status' => 'error'], 403);
            }
            abort(403, 'Akses ditolak');
        }

        // Validasi input
        $request->validate([
            'nama' => 'required|string',
            'deskripsi' => 'required|string',
            'tipe' => 'required|in:jual,donasi',
            'harga' => 'nullable|numeric',
            'id_kategori' => 'required|exists:kategori,id',
            'foto.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Foto tidak lagi 'required' untuk update
        ], [
            'foto.*.image' => 'File harus berupa gambar.',
            'foto.*.mimes' => 'Format gambar yang diperbolehkan adalah jpeg, png, jpg.',
            'foto.*.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $barang->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'harga' => $request->tipe === 'jual' ? $request->harga : null,
            'id_kategori' => $request->id_kategori,
        ]);

        // Tambah foto baru jika ada
        if ($request->hasFile('foto')) {
            // Opsional: Hapus foto lama sebelum menambahkan yang baru jika Anda ingin mengganti semua foto.
            // foreach ($barang->foto as $oldFoto) {
            //     Storage::disk('public')->delete($oldFoto->url_foto);
            //     $oldFoto->delete();
            // }
            foreach ($request->file('foto') as $file) {
                $path = $file->store('barang', 'public');
                Foto::create([
                    'id_barang' => $barang->id,
                    'url_foto' => $path,
                ]);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Barang berhasil diperbarui!',
                'status' => 'success',
                'redirect' => route('profil') // Redirect setelah sukses
            ], 200);
        }
        return redirect()->route('profil')->with('success', 'Barang berhasil diperbarui!');
    }
}