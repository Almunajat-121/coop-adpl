<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Foto;

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
        if (session('role') !== 'pengguna') {
            return redirect('/login');
        }
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'required',
            'tipe' => 'required|in:jual,donasi',
            'harga' => 'nullable|numeric',
            'id_kategori' => 'required|exists:kategori,id',
            'foto' => 'required',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $barang = Barang::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'harga' => $request->tipe === 'jual' ? $request->harga : null,
            'id_pengguna' => session('user')->id,
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
        return redirect()->route('profil')->with('success', 'Barang berhasil diunggah!');
    }
}
