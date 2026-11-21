<!doctype html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <title>Inventory & Warehouse Management System — Prototype</title>

  <!-- Color Mode Init Script (AdminLTE 4) -->
  <script>
    (() => {
      'use strict';
      const root = document.documentElement;
      const STORAGE_KEY = 'lte-theme';
      let stored = null;
      try {
        stored = localStorage.getItem(STORAGE_KEY);
      } catch {}
      const authored = root.getAttribute('data-bs-theme');
      let resolved = 'light';
      if (stored === 'dark' || stored === 'light') {
        resolved = stored;
      } else if (authored === 'dark' || authored === 'light') {
        resolved = authored;
      } else if (globalThis.matchMedia && globalThis.matchMedia('(prefers-color-scheme: dark)').matches) {
        resolved = 'dark';
      }
      root.setAttribute('data-bs-theme', resolved);
      root.style.colorScheme = resolved;
    })();
  </script>

  <!-- Google Fonts: Source Sans 3 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <!-- OverlayScrollbars -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
  <!-- AdminLTE 4 Main CSS -->
  <link rel="stylesheet" href="{{ asset('theme/AdminLTE-4/dist/css/adminlte.min.css') }}" />

  <style>
    body {
      font-family: 'Source Sans 3', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    .hero-card {
      background: linear-gradient(135deg, rgba(13, 110, 253, 0.08) 0%, rgba(13, 202, 240, 0.08) 100%);
      border: 1px solid rgba(13, 110, 253, 0.2);
    }
  </style>
</head>
<body class="bg-body-tertiary">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-body border-bottom shadow-sm">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
        <img src="{{ asset('theme/AdminLTE-4/dist/assets/img/AdminLTELogo.png') }}" alt="Logo" class="rounded" width="32" height="32" />
        <span class="fw-bold text-primary">INVENTORY</span>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Prototype v1.0</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active fw-semibold" href="{{ url('/') }}"><i class="bi bi-house me-1"></i> Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
          </li>
        </ul>

        <div class="d-flex align-items-center gap-3">
          <!-- Dark / Light mode toggle -->
          <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center gap-1" id="bd-theme" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-circle-half"></i>
              <span class="d-none d-sm-inline ms-1">Tema</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="bd-theme">
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="light">
                  <i class="bi bi-sun-fill text-warning"></i> Terang
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="dark">
                  <i class="bi bi-moon-stars-fill text-info"></i> Gelap
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="auto">
                  <i class="bi bi-circle-half text-secondary"></i> Otomatis (OS)
                </button>
              </li>
            </ul>
          </div>

          <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
            <i class="bi bi-box-arrow-in-right me-1"></i> Buka Prototipe
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content Container -->
  <main class="container py-5">
    <!-- Hero Banner -->
    <div class="p-5 mb-4 rounded-4 hero-card shadow-sm">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-success-subtle text-success border border-success-subtle mb-3 fw-medium small">
            <i class="bi bi-check-circle-fill"></i> Task T0.3 — Integrasi Aset AdminLTE-4 Terverifikasi
          </div>
          <h1 class="display-6 fw-bold mb-3">Inventory & Warehouse Management System</h1>
          <p class="lead text-body-secondary mb-4">
            Prototipe antarmuka interaktif manajemen stok, pergudangan, penerimaan (*inbound*), pengeluaran (*outbound*), serta kartu stok digital berbasis <strong>Laravel 13</strong> dan <strong>AdminLTE 4</strong>.
          </p>
          <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg px-4 shadow-sm">
              <i class="bi bi-grid-fill me-2"></i> Eksplorasi Dashboard
            </a>
            <a href="#spesifikasi" class="btn btn-outline-secondary btn-lg px-4">
              <i class="bi bi-info-circle me-2"></i> Rincian Spesifikasi
            </a>
          </div>
        </div>

        <div class="col-lg-4 text-center">
          <div class="card border-0 shadow-sm p-3 bg-body">
            <div class="card-body">
              <img src="{{ asset('theme/AdminLTE-4/dist/assets/img/AdminLTEFullLogo.png') }}" alt="AdminLTE Logo" class="img-fluid mb-3" style="max-height: 50px;" />
              <h6 class="fw-bold mb-1">AdminLTE 4 Theme Engine</h6>
              <p class="small text-body-secondary mb-3">Bootstrap 5.3 + Vanilla JS + CSS Variables</p>
              <div class="d-flex justify-content-center gap-2">
                <span class="badge bg-success-subtle text-success border">Aset Siap</span>
                <span class="badge bg-info-subtle text-info border">No Broken Link</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modul-Modul Utama Grid -->
    <div class="row g-4 mb-5" id="spesifikasi">
      <div class="col-12">
        <h4 class="fw-bold mb-1"><i class="bi bi-layers-fill text-primary me-2"></i>Modul & Fitur Prototipe</h4>
        <p class="text-body-secondary small mb-4">Fitur utama yang tercakup dalam rencana implementasi prototipe inventaris.</p>
      </div>

      <div class="col-md-4">
        <div class="card h-100 border shadow-sm">
          <div class="card-body">
            <div class="d-inline-flex p-3 rounded-3 bg-primary-subtle text-primary mb-3">
              <i class="bi bi-boxes fs-4"></i>
            </div>
            <h5 class="card-title fw-bold">Master Data & Gudang</h5>
            <p class="card-text text-body-secondary small">
              Katalog SKU barang dengan nomor serial, kategori, satuan (UOM), batas stok minimum, dan pemetaan multi-lokasi rak/gudang.
            </p>
          </div>
          <div class="card-footer bg-transparent border-0 pt-0">
            <span class="badge bg-secondary-subtle text-secondary">T5 & T6</span>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 border shadow-sm">
          <div class="card-body">
            <div class="d-inline-flex p-3 rounded-3 bg-success-subtle text-success mb-3">
              <i class="bi bi-arrow-down-left-square fs-4"></i>
            </div>
            <h5 class="card-title fw-bold">Inbound & Outbound</h5>
            <p class="card-text text-body-secondary small">
              Simulasi penerimaan barang (Goods Receipt) dari supplier dan pengeluaran barang untuk distribusi dengan alur transfer antar gudang.
            </p>
          </div>
          <div class="card-footer bg-transparent border-0 pt-0">
            <span class="badge bg-secondary-subtle text-secondary">T7 & T8</span>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 border shadow-sm">
          <div class="card-body">
            <div class="d-inline-flex p-3 rounded-3 bg-warning-subtle text-warning mb-3">
              <i class="bi bi-clipboard-check fs-4"></i>
            </div>
            <h5 class="card-title fw-bold">Opname & Kartu Stok</h5>
            <p class="card-text text-body-secondary small">
              Pencatatan selisih fisik (*stock adjustment*), kartu stok kronologis per-SKU, evaluasi valuasi aset, dan ekspor simulasi laporan.
            </p>
          </div>
          <div class="card-footer bg-transparent border-0 pt-0">
            <span class="badge bg-secondary-subtle text-secondary">T9 & T10</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card Verifikasi Status Sistem -->
    <div class="card border shadow-sm rounded-3">
      <div class="card-header bg-body border-bottom py-3">
        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-shield-check text-success me-2"></i>Status Baseline Teknis (T0.3)</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3 col-6">
            <div class="p-3 rounded bg-body-tertiary border">
              <div class="text-body-secondary small mb-1">Framework</div>
              <div class="fw-bold">Laravel 13.42.0</div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="p-3 rounded bg-body-tertiary border">
              <div class="text-body-secondary small mb-1">PHP Engine</div>
              <div class="fw-bold">PHP 8.3.28 CLI</div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="p-3 rounded bg-body-tertiary border">
              <div class="text-body-secondary small mb-1">Tema UI</div>
              <div class="fw-bold">AdminLTE 4.0</div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="p-3 rounded bg-body-tertiary border">
              <div class="text-body-secondary small mb-1">Aset Status</div>
              <div class="fw-bold text-success"><i class="bi bi-check-circle me-1"></i> Terhubung Penuh</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="border-top py-4 bg-body text-center text-body-secondary small">
    <div class="container">
      <div>Inventory & Warehouse Management System — Prototype v1.0</div>
      <div class="text-secondary mt-1">Ditenagai oleh Laravel 13 & AdminLTE 4</div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
  <!-- AdminLTE 4 Main JS -->
  <script src="{{ asset('theme/AdminLTE-4/dist/js/adminlte.min.js') }}"></script>

  <!-- Color Mode Event Handler -->
  <script>
    document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
      toggle.addEventListener('click', () => {
        const theme = toggle.getAttribute('data-bs-theme-value');
        const root = document.documentElement;
        if (theme === 'auto') {
          const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
          root.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
          localStorage.removeItem('lte-theme');
        } else {
          root.setAttribute('data-bs-theme', theme);
          localStorage.setItem('lte-theme', theme);
        }
      });
    });
  </script>
</body>
</html>
