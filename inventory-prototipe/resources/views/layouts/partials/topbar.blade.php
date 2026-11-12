<nav class="app-header navbar navbar-expand bg-body shadow-sm">
  <div class="container-fluid">
    <!-- Left Navbar Links -->
    <ul class="navbar-nav align-items-center">
      <!-- Sidebar Pushmenu Toggle -->
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
          <i class="bi bi-list fs-4"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block ms-2">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-medium">
          <i class="bi bi-box-seam me-1"></i> Warehouse Central
        </span>
      </li>
    </ul>

    <!-- Right Navbar Links -->
    <ul class="navbar-nav ms-auto align-items-center gap-2">
      <!-- Role Badge Indicator -->
      <li class="nav-item d-none d-sm-inline-block">
        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
          <i class="bi bi-person-badge me-1"></i> Role: <strong>Warehouse Manager</strong>
        </span>
      </li>

      <!-- Notification Alerts Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" aria-label="Notifications">
          <i class="bi bi-bell"></i>
          <span class="position-absolute top-1 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
            <span class="visually-hidden">Notifikasi Baru</span>
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow">
          <span class="dropdown-header fw-bold">3 Peringatan Stok & Mutasi</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
            <div>
              <div class="small fw-semibold">Stok Menipis: Kardus Packaging A3</div>
              <div class="text-secondary" style="font-size: 0.75rem;">Tersisa 15 Pcs (Min: 50)</div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item d-flex align-items-center gap-2">
            <i class="bi bi-box-arrow-in-down text-success"></i>
            <div>
              <div class="small fw-semibold">Penerimaan PO-2026-089 Masuk</div>
              <div class="text-secondary" style="font-size: 0.75rem;">120 Box dari PT Multi Supplier</div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item text-center small text-primary fw-medium">Lihat Semua Log Transaksi</a>
        </div>
      </li>

      <!-- Theme / Color Mode Switcher -->
      <li class="nav-item dropdown">
        <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Ganti Tema">
          <i class="bi bi-circle-half theme-icon-active"></i>
          <span class="d-none ms-2" id="bd-theme-text">Ganti Tema</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
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
      </li>

      <!-- User Profile Menu -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
          <img src="{{ asset('theme/AdminLTE-4/dist/assets/img/avatar.png') }}" class="user-avatar-sm rounded-circle shadow-sm" alt="User Image" />
          <span class="d-none d-md-inline fw-semibold small">Budi Santoso</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow">
          <li class="p-3 bg-body-tertiary text-center border-bottom">
            <img src="{{ asset('theme/AdminLTE-4/dist/assets/img/avatar.png') }}" class="rounded-circle shadow-sm mb-2" width="60" height="60" alt="Avatar" />
            <h6 class="fw-bold mb-0">Budi Santoso</h6>
            <small class="text-body-secondary">Warehouse Manager</small>
          </li>
          <li>
            <a class="dropdown-item py-2" href="{{ url('/pengaturan') }}"><i class="bi bi-person me-2"></i> Profil Pengguna</a>
          </li>
          <li>
            <a class="dropdown-item py-2" href="{{ url('/') }}"><i class="bi bi-house me-2"></i> Kembali ke Beranda</a>
          </li>
          <li><hr class="dropdown-divider my-1"></li>
          <li>
            <button class="dropdown-item py-2 text-danger" onclick="window.showToast('Simulasi logout berhasil', 'info', 'Logout')">
              <i class="bi bi-box-arrow-right me-2"></i> Keluar (Simulasi)
            </button>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
