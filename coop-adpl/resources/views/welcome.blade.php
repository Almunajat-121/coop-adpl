<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reuse &amp; Share - Berbagi Barang Komunitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .hero-bg { background: linear-gradient(120deg, #6ec6ca 0%, #4ca1af 100%); color: #fff; border-radius: 0 0 32px 32px; padding: 2.5rem 0 2rem 0; }
        .hero-btn { background: #3ec6b8; color: #fff; border: none; border-radius: 8px; padding: 0.7rem 2.5rem; font-size: 1.2rem; box-shadow: 0 2px 8px #0001; transition: 0.2s; }
        .hero-btn:hover { background: #2bb3a3; }
        .card-feature { border-radius: 18px; box-shadow: 0 2px 8px #0001; }
        .navbar-custom { background: #fff; border-bottom: 1px solid #e0e0e0; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom mb-0">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">
      <img src="https://cdn-icons-png.flaticon.com/512/3062/3062634.png" width="36" class="me-2">Reuse &amp; share
    </a>
    <div class="ms-auto d-flex align-items-center gap-2">
      <a href="#" class="btn btn-link">Jelajahi</a>
      <a href="{{ route('barang.create') }}" class="btn btn-link">unggah</a>
      <a href="{{ route('register') }}" class="btn btn-outline-success">Registrasi</a>
    </div>
  </div>
</nav>
<div class="hero-bg">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-7">
        <h1 class="fw-bold mb-3" style="font-size:2.5rem;">Berbagi Barang<br>Secara Komunitas</h1>
        <a href="{{ route('login') }}" class="hero-btn mb-4">masuk</a>
      </div>
      <div class="col-md-5 text-center">
        <img src="https://cdn.dribbble.com/users/1787323/screenshots/15423336/media/7e2e2e2e2e2e2e2e2e2e2e2e2e2e2e2e.png" alt="Ilustrasi Komunitas" class="img-fluid" style="max-height:220px;">
      </div>
    </div>
    <div class="row mt-4 g-3">
      <div class="col-md-4">
        <div class="card card-feature p-3 text-center h-100">
          <div class="mb-2"><i class="bi bi-box-arrow-up" style="font-size:2rem;"></i></div>
          <h5 class="fw-bold">Bagikan Barang</h5>
          <div>Unggah barang yang ingin Anda bagikan kepada sesama</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-feature p-3 text-center h-100">
          <div class="mb-2"><i class="bi bi-geo-alt" style="font-size:2rem;"></i></div>
          <h5 class="fw-bold">Jelajahi Komunitas</h5>
          <div>Temukan barang yang di up oleh anggota komunitas</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-feature p-3 text-center h-100">
          <div class="mb-2"><i class="bi bi-clock-history" style="font-size:2rem;"></i></div>
          <h5 class="fw-bold">Lacak Riwayat</h5>
          <div>Pantau riwayat barang pada pemesanan</div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container mt-5">
  <h4 class="fw-bold mb-3">Aktivitas Komunitas</h4>
  <div class="row g-3">
    <div class="col-md-6">
      <img src="https://cdn.dribbble.com/users/1787323/screenshots/15423336/media/7e2e2e2e2e2e2e2e2e2e2e2e2e2e2e2e.png" alt="Aktivitas" class="img-fluid rounded-4">
    </div>
    <div class="col-md-6">
      <div class="card card-feature p-3 h-100">
        <div class="d-flex align-items-center mb-2">
          <img src="https://ui-avatars.com/api/?name=Agus" class="rounded-circle me-2" width="48" height="48">
          <div class="fw-bold">Agus</div>
        </div>
        <div>Platform ini sangat membantu saya dalam membeli barang yang saya butuhkan tanpa harus membeli barang</div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
