<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foto;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    public function destroy($id)
    {
        $foto = Foto::findOrFail($id);
        $barang = $foto->barang;
        // Validasi: tidak boleh hapus jika hanya tersisa satu foto
        if ($barang->foto()->count() <= 1) {
            return back()->with('error', 'Minimal harus ada satu foto barang!');
        }
        // Hapus file fisik
        if ($foto->url_foto && Storage::disk('public')->exists($foto->url_foto)) {
            Storage::disk('public')->delete($foto->url_foto);
        }
        $foto->delete();
        return back()->with('success', 'Foto berhasil dihapus!');
    }
    public function store(Request $request, $barangId)
    {
        $request->validate([
            'foto' => 'required',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $barang = \App\Models\Barang::findOrFail($barangId);
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('barang', 'public');
                \App\Models\Foto::create([
                    'id_barang' => $barang->id,
                    'url_foto' => $path,
                ]);
            }
        }
        return back()->with('success', 'Foto berhasil ditambahkan!');
    }
}
