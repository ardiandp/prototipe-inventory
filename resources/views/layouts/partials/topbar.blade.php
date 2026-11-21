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

    <!-- Center/Right: Role Switcher & Utilities -->
    <ul class="navbar-nav ms-auto align-items-center gap-2">
      <!-- Role Switcher Interactive Dropdown -->
      <li class="nav-item dropdown">
        <button class="btn btn-outline-{{ $currentRole['badge'] }} btn-sm dropdown-toggle d-flex align-items-center gap-1 shadow-sm px-2 px-md-3 py-1" type="button" id="roleSwitcherDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi {{ $currentRole['icon'] }}"></i>
          <span class="d-none d-sm-inline fw-semibold">{{ $currentRole['name'] }}</span>
          <span class="badge bg-{{ $currentRole['badge'] }} text-white ms-1 d-none d-md-inline" style="font-size: 0.7rem;">Demo</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg py-2" aria-labelledby="roleSwitcherDropdown" style="min-width: 290px;">
          <li class="px-3 py-1">
            <div class="small fw-bold text-uppercase text-body-secondary" style="font-size: 0.7rem; letter-spacing: 0.5px;">
              Pilih Peran Pengguna (Role Switcher)
            </div>
            <p class="text-secondary small mb-2" style="font-size: 0.75rem;">
              Simulasi hak akses dan visibilitas menu sesuai tanggung jawab:
            </p>
          </li>
          <li><hr class="dropdown-divider my-1"></li>
          @foreach($allRoles as $roleKey => $roleData)
            <li>
              <a class="dropdown-item d-flex align-items-start gap-2 py-2 {{ $currentRole['id'] === $roleKey ? 'bg-body-secondary active-role-item' : '' }}" href="{{ url('/switch-role/' . $roleKey) }}">
                <div class="p-2 rounded bg-{{ $roleData['badge'] }}-subtle text-{{ $roleData['badge'] }} flex-shrink-0 mt-1">
                  <i class="bi {{ $roleData['icon'] }} fs-6"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center justify-content-between">
                    <span class="fw-bold small text-body">{{ $roleData['name'] }}</span>
                    @if($currentRole['id'] === $roleKey)
                      <span class="badge bg-{{ $roleData['badge'] }} text-white rounded-pill" style="font-size: 0.65rem;">Aktif</span>
                    @endif
                  </div>
                  <div class="text-body-secondary" style="font-size: 0.72rem; line-height: 1.2;">
                    {{ $roleData['description'] }}
                  </div>
                </div>
              </a>
            </li>
          @endforeach
        </ul>
      </li>

      <!-- Notification Alerts Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" aria-label="Notifications">
          <i class="bi bi-bell"></i>
          @if($inventoryStats['alert_count'] > 0)
            <span class="position-absolute top-1 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
              {{ $inventoryStats['alert_count'] }}
            </span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow">
          <span class="dropdown-header fw-bold">Pemberitahuan Sistem ({{ $inventoryStats['alert_count'] }})</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
            <div>
              <div class="small fw-semibold">Peringatan Stok Menipis</div>
              <div class="text-secondary" style="font-size: 0.75rem;">{{ $inventoryStats['alert_count'] }} SKU berada di bawah batas minimum</div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item d-flex align-items-center gap-2">
            <i class="bi bi-box-arrow-in-down text-success"></i>
            <div>
              <div class="small fw-semibold">Penerimaan Barang Siap Input</div>
              <div class="text-secondary" style="font-size: 0.75rem;">Goods Receipt No. GR-2026-004</div>
            </div>
          </a>
        </div>
      </li>

      <!-- Theme / Color Mode Switcher -->
      <li class="nav-item dropdown">
        <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Ganti Tema">
          <i class="bi bi-circle-half theme-icon-active"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme">
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
          <img src="{{ asset('theme/AdminLTE-4/dist/assets/img/' . $currentRole['avatar']) }}" class="user-avatar-sm rounded-circle shadow-sm" alt="User Image" />
          <span class="d-none d-md-inline fw-semibold small">{{ $currentRole['user_name'] }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow">
          <li class="p-3 bg-body-tertiary text-center border-bottom">
            <img src="{{ asset('theme/AdminLTE-4/dist/assets/img/' . $currentRole['avatar']) }}" class="rounded-circle shadow-sm mb-2" width="60" height="60" alt="Avatar" />
            <h6 class="fw-bold mb-0">{{ $currentRole['user_name'] }}</h6>
            <span class="badge bg-{{ $currentRole['badge'] }}-subtle text-{{ $currentRole['badge'] }} border border-{{ $currentRole['badge'] }}-subtle mt-1">
              {{ $currentRole['name'] }}
            </span>
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

@if(session('role_switched'))
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      if (window.showToast) {
        window.showToast('Hak akses disesuaikan untuk peran {{ session("role_switched") }}.', 'info', 'Peran Diperbarui');
      }
    });
  </script>
@endif
