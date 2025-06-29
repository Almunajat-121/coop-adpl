@extends('layouts.app') {{-- Menggunakan layouts.app sebagai layout utama --}}

@push('styles')
    {{-- Kita sertakan home.css khusus untuk styling halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
    {{-- Catatan: meta csrf-token sudah dimuat di layouts.app --}}

    {{-- Header untuk halaman setelah login, dengan tombol profil --}}
    <header class="header">
        <a href="{{ route('home') }}" class="logo" style="text-decoration: none">Reuse & share</a>
        <div style="position: relative; display: inline-block;">
            @php
                // Pastikan Anda mendapatkan objek pengguna yang benar dari sesi
                $penggunaSession = Session::get('user'); // Ini seharusnya objek Akun Pengguna
                $roleSession = Session::get('role'); // Role pengguna
            @endphp

            @if ($penggunaSession && $roleSession === 'pengguna') {{-- Cek apakah pengguna sudah login dan rolenya pengguna --}}
                <div onclick="toggleDropdown()" style="display: flex; align-items: center; cursor: pointer;">
                     <p style="margin-right: 10px;">{{ $penggunaSession->akun->nama ?? 'Pengguna' }}</p>
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
                {{-- Jika belum login, tampilkan tombol login/daftar --}}
                <div class="nav-links">
                    <a href="{{ route('login') }}" class="btn" style="background: #16a085; color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;">Login / Daftar</a>
                </div>
            @endif
        </div>
    </header>

    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Berbagi Barang<br>Secara Komunitas</h1>
                <div class="hero-tag">Mari berbagi bersama</div>
            </div>
            <div class="hero-illustration">
                <div class="person-char person1"></div>
                <div class="person-char person2"></div>
                <div class="person-char person3"></div>
            </div>
        </div>
    </section>

    <section class="search-section">
        <div class="search-bar">
            <input type="text" id="searchInput" class="search-input" placeholder="Cari barang yang Anda butuhkan..." value="{{ request('q') }}">
            <button class="search-btn" id="searchBtn">🔍</button>
        </div>
        <div class="filter-pills">
            <div class="filter-pill active" data-category="Semua">Semua</div>
            {{-- Gunakan data kategori dari controller Anda --}}
            @foreach($kategori as $kat)
                {{-- home.js mengharapkan ID kategori untuk filter API --}}
                <div class="filter-pill {{ request('kategori') == $kat->id ? 'active' : '' }}" data-category="{{ $kat->id }}">{{ $kat->nama }}</div>
            @endforeach
        </div>
    </section>

    <div class="main-content">
        <div class="products-grid" id="productsGrid">
            <p style="text-align:center;">Memuat produk...</p>
        </div>
    </div>

    @push('scripts')
        {{-- Script untuk toggle dropdown profil (diperlukan karena ada @auth) --}}
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