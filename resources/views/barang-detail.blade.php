@extends('layouts.app')

@push('styles')
    {{-- Link ke CSS khusus detail barang dengan nama baru --}}
    <link rel="stylesheet" href="{{ asset('css/barang-detail.css') }}">
@endpush

@section('content')
    <div class="card">
        {{-- Tombol kembali --}}
        <button class="back-button" onclick="goBack()">&larr;</button>

        @php
            // Ambil URL foto dari relasi Barang->Foto
            $gambarArray = $barang->foto->pluck('url_foto')->toArray();
        @endphp

        <div class="image-slider">
            <div class="slider-container" id="sliderContainer">
                @forelse ($gambarArray as $gambar)
                    <img src="{{ asset('storage/' . $gambar) }}" alt="Gambar Produk" />
                @empty
                    <img src="https://via.placeholder.com/600x400?text=No+Image" alt="No Image" />
                @endforelse
            </div>

            @if(count($gambarArray) > 1)
                <button class="slider-nav prev" onclick="changeSlide(-1)">‹</button>
                <button class="slider-nav next" onclick="changeSlide(1)">›</button>

                <div class="slider-dots">
                    @foreach ($gambarArray as $index => $gambar)
                        <div class="dot {{ $index === 0 ? 'active' : '' }}" onclick="currentSlide({{ $index + 1 }})"></div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="content">
            <div class="title">{{ $barang->nama }}</div>
            <div class="price">{{ $barang->tipe == 'jual' ? 'Rp. ' . number_format($barang->harga,0,',','.') : 'Gratis' }}</div>
            <button class="btn btn-outline-danger mb-3" id="btnLaporkan"><i class="bi bi-flag"></i> Laporkan Barang</button>

            <div class="section-title">Keterangan</div>
            <p class="description">
                {{ $barang->deskripsi }}
            </p>

            {{-- Kirim Pesan ke Penjual --}}
            <div class="chat-box">
                <img src="https://img.icons8.com/color/24/whatsapp--v1.png" alt="WhatsApp" />
                <input type="text" placeholder="Apa ini masih ada?" id="messageInput" />
                <a href="https://wa.me/62{{ ltrim($penjual->no_telepon, '0') }}?text=Halo! Saya tertarik dengan {{ $barang->nama }}. Apakah masih tersedia?" target="_blank">Kirim</a>
            </div>

            <div class="info-section">
                <div class="info-card">
                    <div class="section-title">👤 Penjual</div>
                    <div class="profile">
                        <a href="{{ route('penjual.profil', $penjual->id) }}" style="text-decoration:none;color:inherit;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($penjual->akun->nama) }}" alt="User Profile">
                        </a>
                        <div class="profile-info">
                            <strong>{{ $penjual->akun->nama }}</strong>
                            <div class="text-muted small">{{ $penjual->no_telepon }}</div>
                        </div>
                    </div>
                </div>

                <div class="info-card location-card">
                    <div class="section-title">📍 Lokasi Pengambilan</div>
                    <div style="font-size: 14px; line-height: 1.5;">
                        {{ $penjual->alamat }}
                    </div>
                </div>
            </div>

            {{-- Tombol Ajukan Pesanan --}}
            <div class="text-center">
                @if(session('role') === 'pengguna' && $barang->id_pengguna != session('user')->id)
                    <form method="POST" action="{{ route('barang.ajukan', $barang->id) }}">
                        @csrf
                        <button type="submit" class="order-btn">Ajukan pesanan</button>
                    </form>
                @else
                    <button class="order-btn" disabled>Ajukan pesanan</button>
                @endif
            </div>

            {{-- Section Ulasan Pembeli --}}
            @if($ulasan->isNotEmpty())
                <div class="detail-box mb-3" style="margin-top: 30px;">
                    <div class="fw-bold mb-2">Ulasan Pembeli</div>
                    @foreach($ulasan as $u)
                        <div class="mb-2 p-2 border rounded bg-light">
                            <div class="fw-bold">Rating: {{ $u->rating }} / 5</div>
                            <div>{{ $u->isi }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div> {{-- Penutup .content --}}

        {{-- Modal Konfirmasi Pesanan (tetap gunakan logic Anda) --}}
        <div class="modal fade" id="modalKonfirmasiPesanan" tabindex="-1" aria-labelledby="modalKonfirmasiPesananLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalKonfirmasiPesananLabel">Pesanan Sedang Diproses</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p>Barang <b>{{ $barang->nama }}</b> sudah pernah Anda ajukan dan masih dalam proses pemesanan.</p>
                        <p>Apakah Anda ingin melihat status pesanan Anda?</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <a href="{{ route('pesananku') }}" class="btn btn-primary">Lihat Pesananku</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pesan success/error Laravel --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
    </div> {{-- Penutup .card --}}
@endsection

@push('scripts')
    {{-- Memuat barang-detail.js yang menangani slider gambar dan fungsi goBack --}}
    <script src="{{ asset('js/barang-detail.js') }}"></script>
    <script>
        // Logika untuk menampilkan modal konfirmasi pesanan (dari Blade lama Anda)
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('konfirmasi_pesanan'))
                var modal = new bootstrap.Modal(document.getElementById('modalKonfirmasiPesanan'));
                modal.show();
            @endif

            // Logic untuk tombol "Laporkan Barang" (sekarang pakai AJAX)
            const reportButton = document.querySelector('.btn-outline-danger');
            if (reportButton) {
                reportButton.addEventListener('click', function() {
                    const barangId = {{ $barang->id }};
                    const alasan = prompt("Tulis alasan laporan untuk produk ini:");
                    if (alasan === null) return;

                    if (alasan.trim() === "") {
                        alert("Alasan laporan tidak boleh kosong.");
                        return;
                    }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

                    $.ajax({
                        url: `/products/report/${barangId}`,
                        method: "POST",
                        headers: { "X-CSRF-TOKEN": csrfToken },
                        data: { alasan: alasan },
                        success: function (res) {
                            alert(res.message || "Laporan berhasil dikirim.");
                        },
                        error: function (xhr) {
                            let errorMessage = "Terjadi kesalahan saat mengirim laporan.";
                            if (xhr.status === 401) {
                                errorMessage = "Anda harus login sebagai pengguna untuk melapor.";
                                window.location.href = '/login';
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            alert(errorMessage);
                        },
                    });
                });
            }
        });
        // jQuery sudah dimuat di layouts/app.blade.php
    </script>
@endpush