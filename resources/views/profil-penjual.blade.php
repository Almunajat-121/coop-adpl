@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush
@section('content')
    {{-- Navbar dari halaman home --}}
    <header class="header">
        <a href="{{ route('home') }}" class="logo" style="text-decoration: none">Reuse & share</a>
        <div style="position: relative; display: inline-block;">
            @php
                $penggunaSession = Session::get('user');
                $roleSession = Session::get('role');
            @endphp
            @if ($penggunaSession && $roleSession === 'pengguna')
                <div onclick="toggleDropdown()" style="display: flex; align-items: center; cursor: pointer;">
                    <p style="margin-right: 10px;">{{ $penggunaSession->nama ?? 'Pengguna' }}</p>
                    <img src="/img/avatar.png" alt="avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                </div>
                <div id="avatarDropdown"
                    style="display: none; position: absolute; right: 0; background-color: white; border: 1px solid #ccc; min-width: 150px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index: 1; border-radius: 5px;">
                    <a href="{{ route('profil') }}" style="display: block; padding: 10px; text-decoration: none; color: black;">Profil Saya</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        style="display: block; padding: 10px; text-decoration: none; color: black;">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            @else
                <div class="nav-links">
                    <a href="{{ route('login') }}" class="btn" style="background: #16a085; color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;">Login / Daftar</a>
                </div>
            @endif
        </div>
    </header>
<div class="main-content" style="margin-top: 2rem;">
    <div class="container" style="max-width:900px;margin:auto;">
        <div class="profile hero-section" style="display:flex;align-items:center;gap:24px;padding:2rem 2rem 1rem 2rem;border-radius:16px;margin-bottom:2rem;box-shadow:0 2px 8px rgba(0,0,0,0.08);color:white;">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($penjual->akun->nama) }}" class="rounded-circle" width="100" height="100" alt="Foto Penjual">
            <div>
                <h2 style="margin-bottom:0.5rem;">{{ $penjual->akun->nama }}</h2>
                <div style="margin-bottom:0.3rem;"><b>Alamat:</b> {{ $penjual->alamat }}</div>
                <div style="margin-bottom:0.3rem;"><b>No. Telepon:</b> {{ $penjual->no_telepon }}</div>
                <div style="margin-bottom:0.3rem;"><b>Jumlah Barang:</b> {{ $barang->count() }}</div>
            </div>
        </div>
        <h3 style="margin-bottom:1.5rem;margin-left:2rem;">Barang yang Diunggah</h3>
        @if($barang->isEmpty())
            <div class="alert alert-info" style="margin-left:2rem;">Penjual belum mengunggah barang.</div>
        @else
        <div class="products-grid" id="productsGrid" style="margin-left:2rem;margin-right:2rem;">
            @foreach($barang as $b)
            <div class="product-card">
                <a href="{{ route('barang.detail', $b->id) }}" style="text-decoration:none;color:inherit;display:block;height:100%">
                    <div class="product-image" style="background-image:url('{{ $b->foto->first() ? asset('storage/' . $b->foto->first()->url_foto) : 'https://via.placeholder.com/300x200?text=No+Image' }}');"></div>
                    <div class="product-info">
                        <div class="product-title">{{ $b->nama }}</div>
                        <div class="product-description">{{ \Illuminate\Support\Str::limit($b->deskripsi, 60) }}</div>
                        <div class="product-footer">
                            <span class="product-location">{{ $b->tipe == 'jual' ? 'Rp. ' . number_format($b->harga,0,',','.') : 'Gratis' }}</span>
                            <span class="badge bg-{{ $b->status == 'tersedia' ? 'success' : 'danger' }}">{{ ucfirst($b->status) }}</span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('avatarDropdown');
        if (dropdown) {
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }
    }
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('avatarDropdown');
        const avatar = event.target.closest('[onclick="toggleDropdown()"]');
        if (!avatar && dropdown && dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        }
    });
</script>
@endpush
@endsection
