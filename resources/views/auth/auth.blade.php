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
    {{-- Konten login atau register akan di-inject di sini --}}
    @extends('layouts.auth')

@section('content')
    <div class="nav-buttons">
        <button class="nav-btn" onclick="showPage('register')">Registrasi</button>
        <button class="nav-btn active" onclick="showPage('login')">Log in</button>
    </div>

    <div id="register-page" class="page">
        <div class="left-side">
            <div class="header">
                <div class="header-logo">♻</div>
                <span class="header-text">Reuse & share</span>
            </div>

            <div class="main-logo"></div>
            <h1 class="main-title">REUSE & SHARE</h1>
            <p class="main-subtitle">Berbagi Barang, Menyeimbangkan kehidupan.</p>
            <p class="description">
                Bergabunglah dengan komunitas yang peduli lingkungan.
                Berbagi barang bekas, temukan kebutuhan Anda, dan
                ciptakan dunia yang lebih berkelanjutan.
            </p>
        </div>

        <div class="right-side">
            <div class="form-container">
                <h2 class="form-title">Registrasi</h2>
                @session('success')
                    <div style="background-color: #4a9b8e;text-align:center;padding:3px;margin-bottom:3px;border-radius:40px">
                        <p style="color:white">{{ session('success') }}</p>
                    </div>
                @endsession
                @session('error')
                    <div style="background-color: red;text-align:center;padding:3px;margin-bottom:3px;border-radius:40px">
                        <p style="color:white">{{ session('error') }}</p>
                    </div>
                @endsession
                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="nama" placeholder="Nama lengkap" value="{{ old('nama') }}" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="alamat" placeholder="Alamat Lengkap" value="{{ old('alamat') }}" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="no_hp" placeholder="No. Telpon wa/hp no whatsApp" value="{{ old('no_hp') }}" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn-primary">Daftar</button>
                </form>
                <div class="form-footer">
                    <p>Sudah punya akun? <a href="#" onclick="showPage('login')">Log in</a></p>
                </div>
            </div>
        </div>
    </div>

    <div id="login-page" class="page active">
        <div class="left-side">
            <div class="header">
                <div class="header-logo">♻</div>
                <span class="header-text">Reuse & share</span>
            </div>

            <div class="main-logo"></div>
            <h1 class="main-title">REUSE & SHARE</h1>
            <p class="main-subtitle">Berbagi Barang, Menyeimbangkan kehidupan.</p>
            <p class="description">
                Selamat datang kembali! Masuk ke akun Anda dan
                lanjutkan berbagi dengan komunitas peduli lingkungan.
            </p>
        </div>

        <div class="right-side">
            <div class="form-container">
                <h2 class="form-title">Log in</h2>
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn-primary">Log In</button>
                </form>

                <div class="forgot-password">
                    <a href="#">Lupa Password</a>
                </div>

                <div class="form-footer">
                    <p>Baru di Reuse&Share? <a href="#" onclick="showPage('register')">Daftar</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
    @yield('content')

    {{-- Script JS khusus login/register dari ADPL-YAYA --}}
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>