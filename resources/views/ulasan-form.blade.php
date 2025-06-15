@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Beri Ulasan untuk {{ $trx->barang->nama }}</h2>
    <form method="POST" action="{{ route('ulasan.simpan', $trx->id) }}">
        @csrf
        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1-5)</label>
            <select name="rating" id="rating" class="form-control" required>
                <option value="">Pilih rating</option>
                @for($i=1;$i<=5;$i++)
                    <option value="{{ $i }}" {{ (isset($ulasan) && $ulasan->rating == $i) ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="mb-3">
            <label for="isi" class="form-label">Ulasan</label>
            <textarea name="isi" id="isi" class="form-control" rows="4" required>{{ $ulasan->isi ?? '' }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
        <a href="{{ route('pesananku') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
