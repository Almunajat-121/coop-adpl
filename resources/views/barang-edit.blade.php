@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h2>Edit Barang</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('barang.update', $barang->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ $barang->nama }}" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" required>{{ $barang->deskripsi }}</textarea>
        </div>
        <div class="mb-3">
            <label for="tipe" class="form-label">Tipe</label>
            <select class="form-control" id="tipe" name="tipe" required>
                <option value="jual" {{ $barang->tipe == 'jual' ? 'selected' : '' }}>Jual</option>
                <option value="donasi" {{ $barang->tipe == 'donasi' ? 'selected' : '' }}>Donasi (Gratis)</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga (kosongkan jika donasi)</label>
            <input type="number" class="form-control" id="harga" name="harga" step="0.01" value="{{ $barang->harga }}">
        </div>
        <div class="mb-3">
            <label for="id_kategori" class="form-label">Kategori</label>
            <select class="form-control" id="id_kategori" name="id_kategori" required>
                @foreach($kategori as $kat)
                    <option value="{{ $kat->id }}" {{ $barang->id_kategori == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                @endforeach
            </select>
        </div>
        <h4>Daftar Foto Barang</h4>
        <div class="mb-3 d-flex flex-wrap gap-2">
            @foreach($barang->foto as $foto)
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $foto->url_foto) }}" alt="Foto Barang" class="rounded border" style="width:100px;height:100px;object-fit:cover;">
                    <div class="form-check position-absolute" style="top:0;right:0;">
                        <input class="form-check-input" type="checkbox" name="hapus_foto[]" value="{{ $foto->id }}" id="hapus_foto_{{ $foto->id }}">
                        <label class="form-check-label bg-white px-1" for="hapus_foto_{{ $foto->id }}" style="font-size:12px;">Hapus</label>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Tambah Foto Barang (bisa lebih dari satu)</label>
            <input type="file" class="form-control" id="foto" name="foto[]" multiple accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('profil') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipeSelect = document.getElementById('tipe');
    const hargaInput = document.getElementById('harga');
    function toggleHarga() {
        if (tipeSelect.value === 'donasi') {
            hargaInput.value = '';
            hargaInput.disabled = true;
        } else {
            hargaInput.disabled = false;
        }
    }
    tipeSelect.addEventListener('change', toggleHarga);
    toggleHarga();
});
</script>
@endsection
