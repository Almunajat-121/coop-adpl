@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Daftar Laporan Barang</h2>
    @if($laporan->isEmpty())
        <div class="alert alert-info">Belum ada laporan barang.</div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Barang</th>
                    <th>Pelapor</th>
                    <th>Alasan</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporan as $i => $lapor)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('admin.barang.detail', $lapor->barang->id) }}" target="_blank">
                                <img src="{{ $lapor->barang->foto->first() ? asset('storage/' . $lapor->barang->foto->first()->url_foto) : 'https://via.placeholder.com/60x40?text=No+Image' }}" width="60" height="40" style="object-fit:cover;">
                            </a>
                            <div>
                                <div class="fw-bold">{{ $lapor->barang->nama }}</div>
                                <div class="small text-muted">ID: {{ $lapor->barang->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $lapor->pelapor->akun->nama ?? '-' }}</td>
                    <td>{{ $lapor->alasan }}</td>
                    <td>{{ $lapor->created_at ?? '-' }}</td>
                    <td>
                        <form action="{{ route('laporan.hapus', $lapor->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus laporan ini?')">Hapus</button>
                        </form>
                        <form action="{{ route('laporan.abaikan', $lapor->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">Abaikan</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
