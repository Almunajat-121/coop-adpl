<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index() {
        $mahasiswas = Mahasiswa::all();
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function create() {
        return view('mahasiswa.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required',
            'nim' => 'required|unique:mahasiswas',
            'jurusan' => 'required',
        ]);

        Mahasiswa::create($request->all());
        return redirect()->route('mahasiswa.index')->with('success','Data berhasil ditambahkan.');
    }

    public function edit($id) {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama' => 'required',
            'nim' => 'required',
            'jurusan' => 'required',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update($request->all());

        return redirect()->route('mahasiswa.index')->with('success','Data berhasil diupdate.');
    }

    public function destroy($id) {
        Mahasiswa::destroy($id);
        return redirect()->route('mahasiswa.index')->with('success','Data berhasil dihapus.');
    }
}
