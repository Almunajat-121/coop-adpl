@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Pesananku (Pending)</h2>
    @if($pesanan->isEmpty())
        <div class="alert alert-info">Belum ada pesanan yang diajukan.</div>
    @else
    <div class="row row-cols-1 row-cols-md-2 g-4">
        @foreach($pesanan as $trx)
        <div class="col">
            <div class="card h-100">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ $trx->barang->foto->first() ? asset('storage/' . $trx->barang->foto->first()->url_foto) : 'https://via.placeholder.com/200x150?text=No+Image' }}" class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $trx->barang->nama }}</h5>
                            <p class="card-text mb-1">Harga: <span class="fw-bold">{{ $trx->barang->tipe == 'jual' ? 'Rp. ' . number_format($trx->barang->harga,0,',','.') : 'Gratis' }}</span></p>
                            <p class="card-text mb-1">Status: <span class="badge bg-warning text-dark">Pending</span></p>
                            <p class="card-text"><small class="text-muted">
    Diajukan pada 
    @if(!empty($trx->created_at))
        {{ is_object($trx->created_at) ? $trx->created_at->format('d M Y H:i') : '-' }}
    @else
        -
    @endif
</small></p>
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
