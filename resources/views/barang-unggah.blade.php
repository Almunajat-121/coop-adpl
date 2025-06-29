@extends('layouts.app')

@push('styles')
    {{-- Link ke CSS khusus unggah barang --}}
    <link rel="stylesheet" href="{{ asset('css/barang-unggah.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="header">
            {{-- Tombol kembali (kembali ke profil) --}}
            <button class="back-btn" onclick="goBack()">&larr;</button>

            <div class="upload-section">
                <div class="upload-container">
                    <div class="image-slider" id="imageSlider" style="display: none;"> {{-- Awalnya tersembunyi --}}
                        <div class="image-counter" id="imageCounter">0 / 0</div>
                        <div class="slider-container" id="sliderContainer"></div>
                        <button class="slider-nav prev" onclick="changeSlide(-1)">‹</button>
                        <button class="slider-nav next" onclick="changeSlide(1)">›</button>
                        <div class="slider-dots" id="sliderDots"></div>
                    </div>

                    <label class="upload-box" for="upload-image" id="uploadBox">
                        <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <div class="upload-text">Unggah Gambar Produk</div>
                    </label>

                    <button class="add-more-btn" id="addMoreBtn" onclick="document.getElementById('upload-image').click()"
                        style="display: none;"> {{-- Awalnya tersembunyi --}}
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Gambar
                    </button>

                    <input type="file" id="upload-image" name="foto[]" multiple accept="image/*" hidden> {{-- Input file tersembunyi --}}
                </div>
            </div>
        </div>

        <div class="form-section">
            {{-- Pesan sukses/error dari Laravel Session --}}
            @if (session('success'))
                <div style="background: #d4edda; padding: 10px; border-radius: 10px; margin-bottom: 20px; color: #155724;">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div style="background-color: #f8d7da; padding: 10px; border-radius: 10px; margin-bottom: 20px; color: #721c24;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="nama">Nama Produk</label>
                    <input type="text" name="nama" id="nama" placeholder="Masukkan nama produk" value="{{ old('nama') }}" required />
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" placeholder="Deskripsikan produkmu di sini..." required>{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="id_kategori">Kategori Barang</label>
                        <select name="id_kategori" id="id_kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $cat)
                                <option value="{{ $cat->id }}" {{ old('id_kategori') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipe">Tipe Barang</label>
                        <select name="tipe" id="tipe" required>
                            <option value="jual" {{ old('tipe') == 'jual' ? 'selected' : '' }}>Jual</option>
                            <option value="donasi" {{ old('tipe') == 'donasi' ? 'selected' : '' }}>Donasi (Gratis)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" id="harga" placeholder="0" value="{{ old('harga') }}" min="0" />
                </div>

                {{-- Lokasi tidak ada di BarangController::store/update, tapi ada di Pengguna --}}
                {{-- Jika Anda ingin input lokasi manual di sini, Anda perlu menambahkannya ke controller --}}
                {{-- Contoh:
                <div class="form-group">
                    <label for="lokasi_barang">Lokasi Barang</label>
                    <input type="text" name="lokasi_barang" id="lokasi_barang" placeholder="Lokasi spesifik barang" value="{{ old('lokasi_barang') }}" />
                </div>
                --}}

                <button type="submit" class="confirm-btn">Konfirmasi</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Memuat barang-unggah.js yang menangani preview gambar dan submit form via AJAX --}}
    <script src="{{ asset('js/barang-unggah.js') }}"></script>
    <script>
        // Fungsi goBack (dari pesan.js / upload-produk.js)
        function goBack() {
            if (window.history.length > 1) {
                window.location.href = "{{ route('profil') }}"; // Arahkan ke halaman profil
            } else {
                window.location.href = "/"; // Fallback jika tidak ada riwayat
            }
        }

        // Fungsi toggleHarga yang disesuaikan untuk tipe barang
        document.addEventListener('DOMContentLoaded', function() {
            const tipeSelect = document.getElementById('tipe');
            const hargaInput = document.getElementById('harga');

            function toggleHargaInput() {
                if (tipeSelect.value === 'donasi') {
                    hargaInput.value = '0'; // Set harga 0 jika donasi
                    hargaInput.disabled = true;
                } else {
                    hargaInput.disabled = false;
                    if (hargaInput.value === '0') { // Clear if it was 0 from donation, ready for input
                        hargaInput.value = '';
                    }
                }
            }

            tipeSelect.addEventListener('change', toggleHargaInput);
            toggleHargaInput(); // Panggil saat DOMContentLoaded untuk inisialisasi awal
        });
    </script>
@endpush