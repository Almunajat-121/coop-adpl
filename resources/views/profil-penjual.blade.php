@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-3 text-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($penjual->akun->nama) }}" class="rounded-circle mb-2" width="100" height="100">
        </div>
        <div class="col-md-9">
            <h3 class="fw-bold mb-1">{{ $penjual->akun->nama }}</h3>
            <div class="mb-1"><span class="fw-bold">Alamat:</span> {{ $penjual->alamat }}</div>
            <div class="mb-1"><span class="fw-bold">No. Telepon:</span> {{ $penjual->no_telepon }}</div>
        </div>
    </div>
    <hr>
    <h4 class="mb-3">Barang yang Diunggah</h4>
    @if($barang->isEmpty())
        <div class="alert alert-info">Penjual belum mengunggah barang.</div>
    @else
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($barang as $b)
        <div class="col">
            <a href="{{ route('barang.detail', $b->id) }}" style="text-decoration:none;color:inherit;">
            <div class="card h-100">
                <img src="{{ $b->foto->first() ? asset('storage/' . $b->foto->first()->url_foto) : 'https://via.placeholder.com/300x200?text=No+Image' }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ $b->nama }}</h5>
                    <div class="mb-1">Harga: <span class="fw-bold">{{ $b->tipe == 'jual' ? 'Rp. ' . number_format($b->harga,0,',','.') : 'Gratis' }}</span></div>
                    <div class="mb-1">Status: <span class="badge bg-{{ $b->status == 'tersedia' ? 'success' : 'danger' }}">{{ ucfirst($b->status) }}</span></div>
                </div>
            </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
