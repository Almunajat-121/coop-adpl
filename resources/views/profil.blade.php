@extends('layouts.app') {{-- Menggunakan layouts.app sebagai layout utama --}}

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endpush

@section('content')
    {{-- Navbar dari halaman home --}}
    <header class="header">
        <a href="{{ route('home') }}" class="logo" style="text-decoration: none">Reuse & share</a>
        <div style="position: relative; display: inline-block;">
            @php
                $penggunaSession = Session::get('user');
                $roleSession = Session::get('role');
                $avatarUrl = $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=4a9b8e&color=fff';
            @endphp
            @if ($penggunaSession && $roleSession === 'pengguna')
                <div onclick="toggleDropdown()" style="display: flex; align-items: center; cursor: pointer;">
                    <p style="margin-right: 10px;">{{ $penggunaSession->akun->nama ?? 'Pengguna' }}</p>
                    <img src="{{ $avatarUrl }}" alt="avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                </div>
                <div id="avatarDropdown"
                    style="display: none; position: absolute; right: 0; background-color: white; border: 1px solid #ccc; min-width: 150px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index: 1; border-radius: 5px;">
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

    <div class="profile-header">
        {{-- Menggunakan urlencode untuk nama agar URL avatar aman --}}
        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=4a9b8e&color=fff' }}" alt="Avatar" class="profile-avatar">
        <div class="info">
            <h2>{{ $user->nama }}</h2>
            <div class="stats">
                <span>{{ $barang_count }} Barang</span>
                <span>{{ $transaksi_count }} Transaksi</span>
                <span>{{ $rating_avg ?? '-' }} Rating</span>
            </div>
            <div class="buttons">
                <a href="{{ route('barang.create') }}">+ Unggah barang baru</a>
                <a href="{{ route('pesananku') }}">Pesananku</a>
                <a href="{{ route('jualanku') }}">Jualanku</a>
                {{-- Tombol logout dihapus, hanya ada di navbar --}}
            </div>
        </div>
    </div>

    {{-- Navigasi Tab --}}
    <div class="tabs">
        <button class="nav-tab active" id="barang-tab" data-bs-toggle="tab" data-bs-target="#barang" type="button">Barang Saya</button>
        <button class="nav-tab" id="beli-tab" data-bs-toggle="tab" data-bs-target="#beli" type="button">Pesananku</button>
        <button class="nav-tab" id="ulasan-tab" data-bs-toggle="tab" data-bs-target="#ulasan" type="button">Ulasan</button>
    </div>

    <div class="tab-content">
        {{-- Tab Barang Saya --}}
        <div class="tab-pane fade show active" id="barang" role="tabpanel">
            <h3 style="margin-left: 20px; margin-top: 20px;">Daftar Barang yang Dijual</h3>
            @if($barang->isEmpty())
                <div class="alert alert-info" style="margin: 20px;">Tidak ada barang yang dijual.</div>
            @else
                <div class="barang-list">
                    @foreach($barang as $b)
                        <div class="barang-item" style="position: relative;">
                            {{-- Tombol Hapus (jika ingin menampilkannya di sini) --}}
                            {{-- <div style="position: absolute; top: 8px; right: 8px; z-index: 5;">
                                <a href="#" onclick="if(confirm('Yakin ingin menghapus produk ini?')) { event.preventDefault(); document.getElementById('delete-barang-{{$b->id}}').submit(); }"
                                    style="background: #e74c3c; color: white; border: none; border-radius: 4px; padding: 4px 8px; cursor: pointer; font-size: 12px; text-decoration:none">
                                    ✕
                                </a>
                                <form id="delete-barang-{{$b->id}}" action="#" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </div> --}}
                            
                            <a href="{{ route('barang.edit', $b->id) }}" style="text-decoration: none; color: inherit;">
                                <img src="{{ $b->foto->first() ? asset('storage/' . $b->foto->first()->url_foto) : 'https://via.placeholder.com/300x200?text=No+Image' }}" alt="{{ $b->nama }}" class="barang-img">
                                <h4>{{ $b->nama }}</h4>
                                <p>Rp {{ number_format($b->harga,0,',','.') }}</p>
                                <span class="barang-status-badge {{ $b->status == 'tersedia' ? 'status-tersedia' : 'status-lain' }}">
                                    {{ ucfirst($b->status) }}
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Tab Pesananku --}}
        <div class="tab-pane fade" id="beli" role="tabpanel">
            <h5 style="margin-left: 20px; margin-top: 20px;">Pesananku (Transaksi Diajukan)</h5>
            @php
                $pesananku = \App\Models\Transaksi::with(['barang.foto', 'barang.kategori'])
                    ->where('id_pembeli', session('user')->id)
                    ->where('status', 'diajukan')
                    ->orderByDesc('id')->get();
            @endphp
            @if($pesananku->isEmpty())
                <div class="alert alert-info" style="margin: 20px;">Belum ada transaksi yang diajukan.</div>
            @else
            <div class="barang-list"> {{-- Bisa gunakan kelas yang sama atau buat baru jika ingin beda layout --}}
                @foreach($pesananku as $trx)
                <div class="barang-item">
                    <a href="{{ route('barang.detail', $trx->barang->id) }}" style="text-decoration:none;color:inherit;">
                        <img src="{{ $trx->barang->foto->first() ? asset('storage/' . $trx->barang->foto->first()->url_foto) : 'https://via.placeholder.com/300x200?text=No+Image' }}" alt="{{ $trx->barang->nama }}" class="barang-img">
                        <h4>{{ $trx->barang->nama }}</h4>
                        <p>Status: <span class="badge bg-warning text-dark">{{ ucfirst($trx->status) }}</span></p>
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Tab Ulasan --}}
        <div class="tab-pane fade" id="ulasan" role="tabpanel">
            @if($rating_avg) {{-- Cek jika ada rating rata-rata, berarti ada ulasan --}}
                <h5 style="margin-left: 20px; margin-top: 20px;">Rata-rata Rating: {{ $rating_avg }} / 5</h5>
                @php
                    // Jika Anda ingin menampilkan daftar ulasan spesifik di sini,
                    // Anda perlu mengambilnya dari controller (misalnya dari Model Ulasan)
                    // Untuk sementara, kita bisa tampilkan pesan atau rata-rata saja.
                    $ulasanSaya = \App\Models\Ulasan::whereHas('transaksi', function($q) {
                        $q->where('id_pembeli', session('user')->id);
                    })->get();
                @endphp
                @if($ulasanSaya->isNotEmpty())
                    <div class="ulasan-list" style="margin: 20px;">
                        @foreach($ulasanSaya as $ulas)
                            <div class="ulasan-item" style="border: 1px solid #eee; padding: 10px; margin-bottom: 10px; border-radius: 8px;">
                                <strong>Rating: {{ $ulas->rating }} / 5</strong>
                                <p>{{ $ulas->isi }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info" style="margin: 20px;">Belum ada ulasan yang Anda berikan.</div>
                @endif
            @else
                <div class="alert alert-info" style="margin: 20px;">Belum ada ulasan yang relevan.</div>
            @endif
        </div>
    </div>
@endsection

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

        // Inisialisasi Bootstrap Tabs
        document.addEventListener('DOMContentLoaded', function() {
            var triggerTabList = [].slice.call(document.querySelectorAll('#profileTab button'))
            triggerTabList.forEach(function (triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl)

                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            })
        });
    </script>
@endpush