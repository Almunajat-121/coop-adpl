@extends('layouts.app') {{-- Menggunakan layouts.app sebagai layout utama --}}

@push('styles')
    {{-- Kita sertakan pesananku.css khusus untuk styling halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/pesananku.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="header">
            {{-- Navbar/Header utama sudah ada di layouts.app, ini hanya untuk judul dan ilustrasi --}}
            <div class="header-content">
                <div class="header-text">
                    <h1>Pesanan ku</h1>
                </div>
            </div>

            <div class="nav-tabs">
                <button class="nav-tab active" onclick="switchTab('proses')">Proses</button>
                <button class="nav-tab" onclick="switchTab('riwayat')">Riwayat</button>
            </div>
        </div>

        <div class="content">
            {{-- Tab "Dalam Proses" --}}
            <div id="proses-content">
                @php
                    // Ambil id pengguna dari session
                    $userIdAkun = Session::get('user')->id;
                    $userPengguna = \App\Models\Pengguna::where('id_akun', $userIdAkun)->first();
                    $idPembeli = $userPengguna ? $userPengguna->id : null;

                    $pesananProses = \App\Models\Transaksi::with(['barang.foto', 'barang.kategori', 'barang.pengguna.akun'])
                        ->where('id_pembeli', $idPembeli)
                        ->whereIn('status', ['diajukan', 'diterima'])
                        ->orderByDesc('id')
                        ->get();
                @endphp
                @if($pesananProses->isEmpty())
                    <div class="alert alert-info">Tidak ada pesanan yang sedang diproses.</div>
                @else
                    @foreach($pesananProses as $trx)
                        <div class="order-item">
                            <img src="{{ $trx->barang->foto->first() ? asset('storage/' . $trx->barang->foto->first()->url_foto) : 'https://via.placeholder.com/200x200?text=No+Image' }}" alt="{{ $trx->barang->nama }}" class="item-image">
                            <div class="item-details">
                                <div class="item-title">{{ $trx->barang->nama }}</div>
                                <div class="item-meta">
                                    {{-- Anda perlu kolom created_at di tabel transaksi --}}
                                    1 Pesanan | {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}
                                </div>
                                <div class="item-status">
                                    @if($trx->status == 'diajukan')
                                        <div class="status-indicator status-pending">⏳</div>
                                        <span class="status-text">Menunggu Konfirmasi Penjual</span>
                                    @elseif($trx->status == 'diterima')
                                        <div class="status-indicator status-delivered">✓</div>
                                        <span class="status-text">Pesanan Diterima</span>
                                    @endif
                                </div>
                                <div class="item-price">{{ $trx->barang->tipe == 'jual' ? 'Rp. ' . number_format($trx->barang->harga,0,',','.') : 'Gratis' }}</div>
                                <div class="item-actions">
                                    <a href="{{ route('barang.detail', $trx->barang->id) }}" class="btn btn-outline" style="text-decoration:none;">Detail</a>
                                    @if($trx->status == 'diterima')
                                        <a href="https://wa.me/62{{ ltrim($trx->barang->pengguna->no_telepon, '0') }}?text=Halo! Saya ingin konfirmasi pesanan {{ $trx->barang->nama }}." target="_blank" class="btn btn-primary" style="text-decoration:none;">Chat</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Tab "Selesai" --}}
            <div id="riwayat-content" style="display: none;">
                @php
                    $pesananSelesai = \App\Models\Transaksi::with(['barang.foto', 'ulasan'])
                        ->where('id_pembeli', $idPembeli)
                        ->whereIn('status', ['selesai', 'ditolak']) /* Perbaikan komentar di sini */
                        ->orderByDesc('id')
                        ->get();
                @endphp
                @if($pesananSelesai->isEmpty())
                    <div class="alert alert-info">Belum ada pesanan yang selesai atau ditolak.</div>
                @else
                    @foreach($pesananSelesai as $trx)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="review-item-info">
                                    <img src="{{ $trx->barang->foto->first() ? asset('storage/' . $trx->barang->foto->first()->url_foto) : 'https://via.placeholder.com/200x200?text=No+Image' }}" alt="{{ $trx->barang->nama }}" class="review-item-image">
                                    <div class="review-item-details">
                                        <h3>{{ $trx->barang->nama }}</h3>
                                        <div class="review-meta">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</div>
                                    </div>
                                </div>
                                <div class="review-status">
                                    @if($trx->status == 'selesai')
                                        <div class="status-indicator status-delivered">✓</div>
                                        <span class="status-text">Order Delivered</span>
                                    @elseif($trx->status == 'ditolak')
                                        <div class="status-indicator status-cancelled">✖</div>
                                        <span class="status-text">Ditolak</span>
                                    @endif
                                </div>
                                <div class="item-price">{{ $trx->barang->tipe == 'jual' ? 'Rp. ' . number_format($trx->barang->harga,0,',','.') : 'Gratis' }}</div>
                            </div>

                            @if($trx->status == 'selesai')
                                <div class="review-content">
                                    @if($trx->ulasan) {{-- Jika sudah ada ulasan --}}
                                        <div class="review-question">Rating Anda:</div>
                                        <div class="interactive-rating" data-item="ulasan-{{ $trx->id }}">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <div class="interactive-star {{ $i <= $trx->ulasan->rating ? 'active' : '' }}"></div>
                                            @endfor
                                        </div>
                                        <textarea class="review-textarea" data-item="ulasan-{{ $trx->id }}" readonly>{{ $trx->ulasan->isi }}</textarea>
                                        <button class="submit-review-btn" disabled>Ulasan Terkirim</button>
                                    @else {{-- Jika belum ada ulasan --}}
                                        <div class="review-question">Berapa Rating Anda ke Merchant ini?</div>
                                        <div class="interactive-rating" data-item="ulasan-{{ $trx->id }}">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <div class="interactive-star" onclick="setRating('ulasan-{{ $trx->id }}', {{ $i }})"></div>
                                            @endfor
                                        </div>
                                        <textarea class="review-textarea" placeholder="Pelayanan prima, tepat waktu, respon cepat." data-item="ulasan-{{ $trx->id }}"></textarea>
                                        <button class="submit-review-btn" onclick="submitReview('ulasan-{{ $trx->id }}', {{ $trx->id }})">Submit</button>
                                    @endif
                                </div>
                            @elseif($trx->status == 'ditolak')
                                <div class="review-content">
                                    <div class="alert alert-warning">Transaksi ini telah ditolak.</div>
                                    <form method="POST" action="{{ route('transaksi.hapus', $trx->id) }}">
                                        @csrf @method('POST') {{-- Menggunakan POST karena method hapus di TransaksiController --}}
                                        <button type="submit" class="submit-review-btn" style="background-color: #dc3545;">Hapus Riwayat</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div> {{-- Penutup .content --}}

        <div class="modal" id="detailModal">
            <div class="modal-content">
                <button class="close-modal" onclick="closeModal()">&times;</button>
                <h3 id="modalTitle">Detail Barang</h3>
                <p id="modalDescription">Informasi detail tentang barang yang dipilih.</p>
                <button class="btn btn-primary" onclick="closeModal()">Tutup</button>
            </div>
        </div>
    </div> {{-- Penutup .container --}}
@endsection

@push('scripts')
    {{-- Memuat pesananku.js --}}
    <script src="{{ asset('js/pesananku.js') }}"></script>
    <script>
        // Data ulasan awal (sebelum diupdate via AJAX)
        // Ini bisa kosong karena ulasan sekarang diambil dari database.
        const reviewData = {}; 

        // Fungsi switchTab, showDetail, openChat, setRating, updateRatingDisplay, submitReview, closeModal
        // Akan ada di pesananku.js
        
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi status rating jika ada ulasan yang sudah ada
            @foreach($pesananSelesai as $trx)
                @if($trx->ulasan)
                    reviewData['ulasan-{{ $trx->id }}'] = { 
                        rating: {{ $trx->ulasan->rating }}, 
                        review: `{{ $trx->ulasan->isi }}` 
                    };
                    updateRatingDisplay('ulasan-{{ $trx->id }}');
                @endif
            @endforeach
        });
    </script>
@endpush