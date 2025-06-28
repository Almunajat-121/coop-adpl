<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reuse dan Share</title>

    {{-- Link Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Link Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- Meta CSRF Token (selalu diperlukan untuk AJAX) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}"> 

    {{-- Ini akan menjadi tempat untuk CSS spesifik halaman (seperti beranda.css) --}}
    @stack('styles') 
</head>
<body>
    {{-- Konten halaman spesifik akan di-inject di sini --}}
    <main>
        @yield('content')
    </main>

    {{-- Script Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- jQuery (diperlukan oleh home.js) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- Script home.js (untuk memuat produk di section "Barang Terbaru") --}}
    <script src="{{ asset('js/home.js') }}"></script>

    {{-- Ini akan menjadi tempat untuk JS spesifik halaman --}}
    @stack('scripts')
</body>
</html>