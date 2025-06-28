<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reuse & Share</title>
    {{-- Link ke CSS khusus login/register dari ADPL-YAYA --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    {{-- Konten login atau register akan di-inject di sini dari @section('content') --}}
    @yield('content')

    {{-- Script JS khusus login/register dari ADPL-YAYA --}}
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>