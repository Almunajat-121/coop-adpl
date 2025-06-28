<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reuse & Share - @yield('title')</title>

    @if (Request::is('home', 'profile', 'laporan'))
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @endif
    @if (Request::is('pesananku'))
        <link rel="stylesheet" href="{{ asset('css/pesananku.css') }}">
    @endif
    @if (Request::is('pesan/detail/*'))
        <link rel="stylesheet" href="{{ asset('css/pesan.css') }}">
    @endif
    @if (Request::is('upload-produk', 'edit-produk/*', 'profile/*'))
        <link rel="stylesheet" href="{{ asset('css/upload-produk.css') }}">
    @endif
    @if (Request::is('profile'))
        <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @stack('styles')
</head>

<body>
    @if (Request::is('home', 'profile', 'laporan'))
        <header class="header">
            <a href="/home" class="logo" style="text-decoration: none">Reuse & share</a>
            <div style="position: relative; display: inline-block;">
                <div onclick="toggleDropdown()" style="display: flex; align-items: center; cursor: pointer;">
                    <p style="margin-right: 10px;">{{ auth()->user()->nama }}</p>
                    <img src="/img/avatar.png" alt="avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                </div>

                <!-- Dropdown menu -->
                <div id="avatarDropdown"
                    style="display: none; position: absolute; right: 0; background-color: white; border: 1px solid #ccc; min-width: 150px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index: 1; border-radius: 5px;">
                    <a href="/profile"
                        style="display: block; padding: 10px; text-decoration: none; color: black;">Profile</a>
                    <a href="/logout" onclick="return confirm('Apakah anda yakin untuk logout')"
                        style="display: block; padding: 10px; text-decoration: none; color: black;">Logout</a>
                </div>
            </div>

        </header>
    @endif

    @yield('content')

    @if (Request::is('home', 'profile', 'laporanff'))
        <script src="{{ asset('js/home.js') }}"></script>
        <script>
            function toggleDropdown() {
                const dropdown = document.getElementById('avatarDropdown');
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            }

            // Optional: close dropdown if clicked outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('avatarDropdown');
                const avatar = event.target.closest('[onclick="toggleDropdown()"]');
                if (!avatar && dropdown && dropdown.style.display === 'block') {
                    dropdown.style.display = 'none';
                }
            });
        </script>
    @endif
    @if (Request::is('pesananku'))
        <script src="{{ asset('js/pesananku.js') }}"></script>
    @endif
    @if (Request::is('pesan/detail/*'))
        <script src="{{ asset('js/pesan.js') }}"></script>
    @endif
    @if (Request::is('upload-produk', 'edit-produk/*', 'profile/*'))
        <script src="{{ asset('js/upload-produk.js') }}"></script>
    @endif
    @stack('scripts')
</body>

</html>
