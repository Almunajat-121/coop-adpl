@extends('layouts.app')
@section('content')
<div class="container py-4">
    <!-- Header/Navigasi -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Reuse &amp; Share</h2>
    </div>
    <!-- Profil Pengguna -->
    <div class="card mb-4 p-4 text-center">
        <img src="{{ $user->avatar ?? asset('https://ui-avatars.com/api/?name=' . urlencode($user->nama)) }}" class="rounded-circle mb-3" width="120" height="120" alt="Avatar">
        <h4 class="fw-bold">{{ $user->nama }}</h4>
        <div class="mb-2">{{ $user->no_telepon }}</div>
        <div class="mb-2">{{ $user->alamat }}</div>
        <div class="row justify-content-center mb-3">
            <div class="col-auto text-center">
                <div class="fw-bold fs-5">{{ $barang_count }}</div>
                <div class="text-muted">Barang</div>
            </div>
            <div class="col-auto text-center">
                <div class="fw-bold fs-5">{{ $transaksi_count }}</div>
                <div class="text-muted">Transaksi</div>
            </div>
            <div class="col-auto text-center">
                <div class="fw-bold fs-5">{{ $rating_avg ?? '-' }}</div>
                <div class="text-muted">Rating</div>
            </div>
        </div>
        <div class="d-flex justify-content-center gap-2 mb-2">
            <a href="{{ route('barang.create') }}" class="btn btn-success">Unggah Barang Baru</a>
            <a href="{{ route('pesananku') }}" class="btn btn-outline-primary">Pesananku</a>
            <a href="{{ route('jualanku') }}" class="btn btn-outline-info">Jualanku</a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Logout</button>
            </form>
        </div>
    </div>
    <!-- Navigasi Tab -->
    <ul class="nav nav-tabs mb-3" id="profileTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="barang-tab" data-bs-toggle="tab" data-bs-target="#barang" type="button" role="tab">Barang Saya</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="beli-tab" data-bs-toggle="tab" data-bs-target="#beli" type="button" role="tab">Pesananku</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ulasan-tab" data-bs-toggle="tab" data-bs-target="#ulasan" type="button" role="tab">Ulasan</button>
        </li>
    </ul>
    <div class="tab-content" id="profileTabContent">
        <!-- Tab Barang Saya -->
        <div class="tab-pane fade show active" id="barang" role="tabpanel">
            <h5 class="mb-3">Daftar Barang yang Dijual</h5>
            @if($barang->isEmpty())
                <div class="alert alert-info">Tidak ada barang yang dijual.</div>
            @else
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($barang as $b)
                <div class="col">
                    <a href="{{ route('barang.edit', $b->id) }}" style="text-decoration:none;color:inherit;">
                    <div class="card h-100 hover-shadow">
                        <img src="{{ $b->foto->first() ? asset('storage/' . $b->foto->first()->url_foto) : 'https://via.placeholder.com/300x200?text=No+Image' }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">{{ $b->nama }}</h5>
                            <div class="mb-1">Kondisi: <span class="badge bg-secondary">{{ $b->tipe == 'jual' ? 'Bekas' : 'Gratis' }}</span></div>
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
        <!-- Tab Pesananku -->
        <div class="tab-pane fade" id="beli" role="tabpanel">
            <h5 class="mb-3">Pesananku (Transaksi Diajukan)</h5>
            @php
                $pesananku = \App\Models\Transaksi::with(['barang.foto', 'barang.kategori'])
                    ->where('id_pembeli', session('user')->id)
                    ->where('status', 'diajukan')
                    ->orderByDesc('id')->get();
            @endphp
            @if($pesananku->isEmpty())
                <div class="alert alert-info">Belum ada transaksi yang diajukan.</div>
            @else
            <div class="row row-cols-1 row-cols-md-2 g-3">
                @foreach($pesananku as $trx)
                <div class="col">
                    <div class="card h-100">
                        <img src="{{ $trx->barang->foto->first() ? asset('storage/' . $trx->barang->foto->first()->url_foto) : 'https://via.placeholder.com/300x200?text=No+Image' }}" class="card-img-top" style="height:140px;object-fit:cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $trx->barang->nama }}</h5>
                            <div class="mb-1">Kategori: {{ $trx->barang->kategori->nama ?? '-' }}</div>
                            <div class="mb-1">Status: <span class="badge bg-warning text-dark">Diajukan</span></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        <!-- Tab Ulasan -->
        <div class="tab-pane fade" id="ulasan" role="tabpanel">
            <div class="alert alert-info">Belum ada ulasan.</div>
        </div>
    </div>
</div>
@endsection
