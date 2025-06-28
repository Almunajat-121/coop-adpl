@extends('layouts.app') {{-- Landing layout akan memperluas layout app dasar --}}

@push('styles')
    {{-- Hanya di halaman landing, kita sertakan beranda.css --}}
    <link rel="stylesheet" href="{{ asset('css/beranda.css') }}">
@endpush

@section('content')
    <nav class="navbar">
        <div class="logo">Reuse & Share</div>
        <div class="nav-links">
            <a href="{{ route('login') }}" class="btn btn-primary" style="color: white">Login</a>
        </div>
    </nav>

    {{-- Konten dari beranda.blade.php akan masuk di sini --}}
    @yield('landing_content')

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">Tentang Kami</a>
                <a href="#">Cara Kerja</a>
                <a href="#">Syarat & Ketentuan</a>
                <a href="#">Kontak</a>
            </div>
            <p>&copy; 2024 Reuse & Share. Semua hak dilindungi.</p>
        </div>
    </footer>
@endsection