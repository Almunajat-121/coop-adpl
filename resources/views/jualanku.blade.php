@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Jualanku - Barang yang Diajukan Transaksi</h2>
    @if($barang_diajukan->isEmpty())
        <div class="alert alert-info">Belum ada barangmu yang sedang diajukan transaksi.</div>
    @else
    <div class="row g-3">
        @foreach($barang_diajukan as $barang)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <img src="{{ $barang->foto->first() ? asset('storage/' . $barang->foto->first()->url_foto) : 'https://via.placeholder.com/300x200?text=No+Image' }}" class="card-img-top" style="object-fit:cover;max-height:180px;">
                <div class="card-body">
                    <h5 class="card-title">{{ $barang->nama }}</h5>
                    <div class="mb-2"><span class="fw-bold">ID:</span> {{ $barang->id }}</div>
                    <div class="mb-2"><span class="fw-bold">Status:</span> {{ $barang->status ?? '-' }}</div>
                    <div class="mb-2"><span class="fw-bold">Harga:</span> {{ $barang->harga ? 'Rp'.number_format($barang->harga,0,',','.') : '-' }}</div>
                    <div class="mb-2"><span class="fw-bold">Tipe:</span> {{ $barang->tipe ?? '-' }}</div>
                    <div class="mb-2"><span class="fw-bold">Deskripsi:</span> {{ $barang->deskripsi ?? '-' }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
