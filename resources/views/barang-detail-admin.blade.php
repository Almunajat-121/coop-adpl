@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Detail Barang (Admin)</h2>
    <div class="row">
        <div class="col-md-5">
            @if($barang->foto->count())
                <img src="{{ asset('storage/' . $barang->foto->first()->url_foto) }}" class="img-fluid rounded mb-3" style="max-height:300px;object-fit:cover;">
            @else
                <img src="https://via.placeholder.com/400x300?text=No+Image" class="img-fluid rounded mb-3">
            @endif
        </div>
        <div class="col-md-7">
            <h4>{{ $barang->nama }}</h4>
            <div class="mb-2"><span class="fw-bold">ID:</span> {{ $barang->id }}</div>
            <div class="mb-2"><span class="fw-bold">Kategori:</span> {{ $barang->kategori->nama ?? '-' }}</div>
            <div class="mb-2"><span class="fw-bold">Status:</span> {{ $barang->status ?? '-' }}</div>
            <div class="mb-2"><span class="fw-bold">Harga:</span> {{ $barang->harga ? 'Rp'.number_format($barang->harga,0,',','.') : '-' }}</div>
            <div class="mb-2"><span class="fw-bold">Tipe:</span> {{ $barang->tipe ?? '-' }}</div>
            <div class="mb-2"><span class="fw-bold">Deskripsi:</span> {{ $barang->deskripsi ?? '-' }}</div>
            <div class="mb-2"><span class="fw-bold">Pemilik:</span> {{ $barang->pengguna->akun->nama ?? '-' }}</div>
            <div class="mb-2"><span class="fw-bold">No. Telepon:</span> {{ $barang->pengguna->no_telepon ?? '-' }}</div>
            <div class="mb-2"><span class="fw-bold">Alamat:</span> {{ $barang->pengguna->alamat ?? '-' }}</div>
            <div class="mt-4">
                <form action="{{ route('admin.barang.hapus', $barang->id) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus barang ini?')">Hapus Barang</button>
                </form>
            </div>
        </div>
    </div>
    <hr>
    <h5>Foto Lainnya</h5>
    <div class="d-flex flex-wrap gap-2">
        @foreach($barang->foto as $foto)
            <img src="{{ asset('storage/' . $foto->url_foto) }}" width="100" height="70" style="object-fit:cover;" class="rounded">
        @endforeach
    </div>
</div>
@endsection
