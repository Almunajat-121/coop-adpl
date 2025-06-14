@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h2>Unggah Barang</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('barang.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
        </div>
        <div class="mb-3">
            <label for="tipe" class="form-label">Tipe</label>
            <select class="form-control" id="tipe" name="tipe" required>
                <option value="jual">Jual</option>
                <option value="donasi">Donasi (Gratis)</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" step="0.01">
        </div>
        <div class="mb-3">
            <label for="id_kategori" class="form-label">Kategori</label>
            <select class="form-control" id="id_kategori" name="id_kategori" required>
                @foreach($kategori as $kat)
                    <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto Barang</label>
            <input type="file" class="form-control" id="foto" name="foto[]" multiple required accept="image/*">
            <div id="preview" class="mt-2 d-flex flex-wrap gap-2"></div>
        </div>
        <button type="submit" class="btn btn-success">Unggah</button>
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
    toggleHarga(); // initial check

    // Preview multiple images
    const fotoInput = document.getElementById('foto');
    const previewDiv = document.getElementById('preview');
    fotoInput.addEventListener('change', function() {
        previewDiv.innerHTML = '';
        Array.from(fotoInput.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'rounded border';
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    previewDiv.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    });
});
</script>
@endsection
