@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Jualanku - Barang yang Diajukan Transaksi</h2>
    @if($barang_diajukan->isEmpty())
        <div class="alert alert-info">Belum ada barangmu yang sedang diajukan transaksi.</div>
    @else
    <div class="row g-3">
        @foreach($barang_diajukan as $barang)
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ $barang->foto->first() ? asset('storage/' . $barang->foto->first()->url_foto) : 'https://via.placeholder.com/300x200?text=No+Image' }}" class="img-fluid rounded-start" style="object-fit:cover;max-height:180px;">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $barang->nama }}</h5>
                            <div class="mb-2"><span class="fw-bold">ID:</span> {{ $barang->id }}</div>
                            <div class="mb-2"><span class="fw-bold">Status:</span> {{ $barang->status ?? '-' }}</div>
                            <div class="mb-2"><span class="fw-bold">Harga:</span> {{ $barang->harga ? 'Rp'.number_format($barang->harga,0,',','.') : '-' }}</div>
                            <div class="mb-2"><span class="fw-bold">Tipe:</span> {{ $barang->tipe ?? '-' }}</div>
                            <div class="mb-2"><span class="fw-bold">Deskripsi:</span> {{ $barang->deskripsi ?? '-' }}</div>
                            @if($barang->transaksi->count())
                                @foreach($barang->transaksi as $trx)
                                    <hr>
                                    <div class="mb-2"><span class="fw-bold">Pemesan:</span> {{ $trx->pembeli->akun->nama ?? '-' }}</div>
                                    <div class="mb-2"><span class="fw-bold">No. Telepon:</span> {{ $trx->pembeli->no_telepon ?? '-' }}</div>
                                    <div class="mb-2"><span class="fw-bold">Alamat:</span> {{ $trx->pembeli->alamat ?? '-' }}</div>
                                    <div class="mb-2">
                                        <span class="fw-bold">Status Transaksi:</span>
                                        @if($trx->status == 'diajukan')
                                            <span class="badge bg-warning text-dark">Diajukan</span>
                                        @elseif($trx->status == 'diterima')
                                            <span class="badge bg-success">Diterima</span>
                                        @elseif($trx->status == 'selesai')
                                            <span class="badge bg-primary">Selesai</span>
                                        @elseif($trx->status == 'ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($trx->status) }}</span>
                                        @endif
                                    </div>
                                    @if($trx->status == 'diajukan')
                                    <form action="{{ route('transaksi.terima', $trx->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Terima transaksi ini?')">Terima</button>
                                    </form>
                                    <form action="{{ route('transaksi.tolak', $trx->id) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak transaksi ini?')">Tolak</button>
                                    </form>
                                    @endif
                                    @if($trx->status == 'diterima')
                                    <form action="{{ route('transaksi.selesai', $trx->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Tandai transaksi selesai?')">Selesaikan</button>
                                    </form>
                                    @endif
                                    @if($trx->status == 'selesai' && $trx->ulasan)
                                    <div class="mt-2 p-2 border rounded bg-light">
                                        <span class="fw-bold">Ulasan Pembeli:</span><br>
                                        <span class="fw-bold">Rating:</span> {{ $trx->ulasan->rating }}<br>
                                        <span class="fw-bold">Ulasan:</span> {{ $trx->ulasan->isi }}
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
