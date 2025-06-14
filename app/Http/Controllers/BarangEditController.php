<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Foto;

class BarangEditController extends Controller
{
    public function edit($id)
    {
        if (session('role') !== 'pengguna') {
            return redirect('/login');
        }
        $barang = Barang::with('foto')->findOrFail($id);
        if ($barang->id_pengguna != session('user')->id) {
            abort(403, 'Akses ditolak');
        }
        $kategori = Kategori::all();
        return view('barang-edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        if (session('role') !== 'pengguna') {
            return redirect('/login');
        }
        $barang = Barang::findOrFail($id);
        if ($barang->id_pengguna != session('user')->id) {
            abort(403, 'Akses ditolak');
        }
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'required',
            'tipe' => 'required|in:jual,donasi',
            'harga' => 'nullable|numeric',
            'id_kategori' => 'required|exists:kategori,id',
        ]);
        $barang->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'harga' => $request->tipe === 'jual' ? $request->harga : null,
            'id_kategori' => $request->id_kategori,
        ]);
        return redirect()->route('profil')->with('success', 'Barang berhasil diperbarui!');
    }
}
