@extends('layouts.app')
@section('content')
<style>
.hero-bg { background: linear-gradient(120deg, #6ec6ca 0%, #4ca1af 100%); color: #fff; border-radius: 0 0 32px 32px; padding: 2.5rem 0 2rem 0; }
.card-feature { border-radius: 18px; box-shadow: 0 2px 8px #0001; }
.category-btn { border: none; background: #f5f5f5; border-radius: 50px; padding: 0.5rem 1.2rem; margin-right: 0.5rem; margin-bottom: 0.5rem; font-size: 1rem; transition: 0.2s; }
.category-btn.active, .category-btn:hover { background: #4ca1af; color: #fff; }
.product-card { border-radius: 18px; box-shadow: 0 2px 8px #0001; transition: 0.2s; }
.product-card:hover { box-shadow: 0 4px 16px #0002; }
</style>
<nav class="navbar navbar-expand-lg navbar-custom mb-0">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">
      <img src="https://cdn-icons-png.flaticon.com/512/3062/3062634.png" width="36" class="me-2">Reuse &amp; share
    </a>
    <div class="ms-auto d-flex align-items-center gap-2">
      <a href="{{ route('profil') }}" class="btn btn-outline-secondary rounded-circle"><i class="bi bi-person-circle"></i></a>
    </div>
  </div>
</nav>
<div class="hero-bg mb-4">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-7">
        <h1 class="fw-bold mb-3" style="font-size:2.5rem;">Berbagi Barang<br>Secara Komunitas</h1>
        <a href="{{ route('barang.create') }}" class="btn btn-success mb-4">Mulai Berbagi Sekarang</a>
      </div>
      <div class="col-md-5 text-center">
        <img src="https://cdn.dribbble.com/users/1787323/screenshots/15423336/media/7e2e2e2e2e2e2e2e2e2e2e2e2e2e2e2e.png" alt="Ilustrasi Komunitas" class="img-fluid" style="max-height:220px;">
      </div>
    </div>
    <form method="GET" action="{{ route('home') }}" class="mt-4">
      <div class="input-group input-group-lg">
        <input type="text" class="form-control" name="q" placeholder="Cari barang yang anda butuh..." value="{{ request('q') }}">
        <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
      </div>
    </form>
  </div>
</div>
<div class="container mb-4">
  <div class="mb-2 fw-bold">Filter</div>
  <div class="d-flex flex-wrap mb-4">
    <a href="{{ route('home') }}" class="category-btn{{ !request('kategori') ? ' active' : '' }}">Semua</a>
    @foreach($kategori as $kat)
      <a href="{{ route('home', array_merge(request()->except('page'), ['kategori' => $kat->id])) }}" class="category-btn{{ request('kategori') == $kat->id ? ' active' : '' }}">{{ $kat->nama }}</a>
    @endforeach
  </div>
  <div class="mb-3 fw-bold">Terbaru</div>
  @if($barang->isEmpty())
    <div class="alert alert-info">Belum ada barang tersedia.</div>
  @else
    <div class="row row-cols-2 row-cols-md-4 g-3 mb-5">
      @foreach($barang as $b)
      <div class="col">
        <a href="{{ route('barang.detail', $b->id) }}" style="text-decoration:none;color:inherit;">
        <div class="card product-card h-100">
          <img src="{{ $b->foto->first() ? asset('storage/' . $b->foto->first()->url_foto) : 'https://via.placeholder.com/300x200?text=No+Image' }}" class="card-img-top" style="height:160px;object-fit:cover;">
          <div class="card-body p-2">
            <div class="fw-bold small mb-1">{{ $b->nama }}</div>
            <div class="text-muted small">{{ $b->kategori->nama ?? '-' }}</div>
            <div class="small">{{ $b->tipe == 'jual' ? 'Rp. ' . number_format($b->harga,0,',','.') : 'Gratis' }}</div>
          </div>
        </div>
        </a>
      </div>
      @endforeach
    </div>
  @endif
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
