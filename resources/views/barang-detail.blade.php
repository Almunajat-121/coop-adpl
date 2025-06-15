@extends('layouts.app')
@section('content')
<style>
.detail-img-carousel { border-radius: 18px; overflow: hidden; }
.detail-img-carousel img { width: 100%; height: 320px; object-fit: cover; }
.detail-box { border-radius: 18px; box-shadow: 0 2px 8px #0001; padding: 1.2rem; margin-bottom: 1.2rem; background: #fff; }
.detail-btn { background: #3ec6b8; color: #fff; border: none; border-radius: 8px; padding: 0.7rem 2.5rem; font-size: 1.1rem; box-shadow: 0 2px 8px #0001; transition: 0.2s; }
.detail-btn:hover { background: #2bb3a3; }
</style>
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div id="carouselFoto" class="carousel slide detail-img-carousel mb-3" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($barang->foto as $i => $foto)
                    <div class="carousel-item{{ $i == 0 ? ' active' : '' }}">
                        <img src="{{ asset('storage/' . $foto->url_foto) }}" alt="Foto Barang">
                    </div>
                    @endforeach
                </div>
                @if($barang->foto->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselFoto" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselFoto" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
                @endif
            </div>
        </div>
    </div>
    <div class="row mb-2 align-items-center">
        <div class="col">
            <h3 class="fw-bold">{{ $barang->nama }}</h3>
            <div class="text-muted mb-2">{{ $barang->tipe == 'jual' ? 'Rp. ' . number_format($barang->harga,0,',','.') : 'Gratis' }}</div>
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalLaporkan"><i class="bi bi-flag"></i> Laporkan Barang</button>
        </div>
    </div>
    <!-- Modal Laporkan Barang -->
    <div class="modal fade" id="modalLaporkan" tabindex="-1" aria-labelledby="modalLaporkanLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" action="{{ route('barang.lapor', $barang->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLaporkanLabel">Laporkan Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan Laporan</label>
                        <textarea class="form-control" id="alasan" name="alasan" required minlength="5" placeholder="Jelaskan alasan laporan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Kirim Laporan</button>
                </div>
            </div>
        </form>
      </div>
    </div>
    <div class="detail-box mb-3">
        <div class="fw-bold mb-1">Keterangan</div>
        <div>{{ $barang->deskripsi }}</div>
    </div>
    <div class="detail-box mb-3">
        <div class="fw-bold mb-2"><i class="bi bi-whatsapp text-success"></i> Kirim pesan ke penjual</div>
        <form action="https://wa.me/62{{ ltrim($penjual->no_telepon, '0') }}" method="get" target="_blank" class="d-flex align-items-center gap-2">
            <input type="text" class="form-control" name="text" value="Apa ini masih ada?" style="border-radius: 50px;">
            <button type="submit" class="btn btn-success" style="border-radius: 50px;">Kirim</button>
        </form>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="detail-box h-100">
                <div class="fw-bold mb-1">Informasi Penjual</div>
                <a href="{{ route('penjual.profil', $penjual->id) }}" style="text-decoration:none;color:inherit;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($penjual->akun->nama) }}" class="rounded-circle" width="40" height="40">
                        <div>
                            <div class="fw-bold">{{ $penjual->akun->nama }}</div>
                            <div class="text-muted small">{{ $penjual->alamat }}</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="detail-box h-100">
                <div class="fw-bold mb-1">Alamat pertemuan</div>
                <div><i class="bi bi-geo-alt text-danger"></i> {{ $penjual->alamat }}</div>
            </div>
        </div>
    </div>
    <div class="text-end">
        @if(session('role') === 'pengguna')
        <form method="POST" action="{{ route('barang.ajukan', $barang->id) }}">
            @csrf
            <button type="submit" class="detail-btn">Ajukan pesanan</button>
        </form>
        @else
        <button class="detail-btn" disabled>Ajukan pesanan</button>
        @endif
    </div>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($ulasan->count())
    <div class="detail-box mb-3">
        <div class="fw-bold mb-2">Ulasan Pembeli</div>
        @foreach($ulasan as $u)
            <div class="mb-2 p-2 border rounded bg-light">
                <div class="fw-bold">Rating: {{ $u->rating }} / 5</div>
                <div>{{ $u->isi }}</div>
            </div>
        @endforeach
    </div>
    @endif
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
