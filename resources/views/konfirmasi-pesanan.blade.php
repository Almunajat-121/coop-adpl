@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="alert alert-warning text-center">
        <h4 class="mb-3">Pesanan Sedang Diproses</h4>
        <p>Barang <b>{{ $barang->nama }}</b> sudah pernah Anda ajukan dan masih dalam proses pemesanan.</p>
        <p>Apakah Anda ingin melihat status pesanan Anda?</p>
        <a href="{{ route('pesananku') }}" class="btn btn-primary">Lihat Pesananku</a>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
