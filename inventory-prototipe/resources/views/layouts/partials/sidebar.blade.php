@php
  use App\Support\RoleManager;
@endphp

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!-- Sidebar Brand -->
  <div class="sidebar-brand">
    <a href="{{ url('/dashboard') }}" class="brand-link d-flex align-items-center gap-2 text-decoration-none">
      <img src="{{ asset('theme/AdminLTE-4/dist/assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow-sm rounded" />
      <span class="brand-text fw-bold text-white fs-6">INVENTORY <span class="badge text-bg-primary fs-8 py-0 px-1">PROTOTYPE</span></span>
    </a>
  </div>

  <!-- Sidebar Wrapper -->
  <div class="sidebar-wrapper">
    <!-- User Quick Info Panel -->
    <div class="user-panel py-3 px-3 d-flex align-items-center border-bottom border-secondary-subtle">
      <div class="image">
        <img src="{{ asset('theme/AdminLTE-4/dist/assets/img/' . $currentRole['avatar']) }}" class="rounded-circle shadow-sm" width="36" height="36" alt="User Image" />
      </div>
      <div class="info ms-3 overflow-hidden">
        <div class="d-block text-white fw-semibold small text-truncate">{{ $currentRole['user_name'] }}</div>
        <div class="d-flex align-items-center gap-1">
          <span class="badge bg-{{ $currentRole['badge'] }}-subtle text-{{ $currentRole['badge'] }} border border-{{ $currentRole['badge'] }}-subtle" style="font-size: 0.65rem;">
            {{ $currentRole['name'] }}
          </span>
        </div>
      </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-2" aria-label="Main Navigation">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
        <!-- Header: Utama -->
        <li class="nav-header">Utama</li>
        <li class="nav-item">
          <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard*') || request()->is('/') ? 'active' : '' }}">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Header: Master Data -->
        <li class="nav-header">Master Data</li>
        <li class="nav-item">
          @if(RoleManager::can('master_barang'))
            <a href="{{ url('/master/barang') }}" class="nav-link {{ request()->is('master/barang*') ? 'active' : '' }}">
              <i class="nav-icon bi bi-boxes"></i>
              <p>Katalog Barang & SKU</p>
            </a>
          @else
            <a href="javascript:void(0)" class="nav-link text-body-secondary opacity-50" onclick="window.showToast('Akses Katalog Barang dibatasi untuk peran {{ $currentRole['name'] }}', 'warning', 'Akses Terbatas')">
              <i class="nav-icon bi bi-boxes"></i>
              <p>Katalog Barang <i class="bi bi-lock-fill float-end text-secondary small"></i></p>
            </a>
          @endif
        </li>

        <li class="nav-item">
          @if(RoleManager::can('master_lokasi'))
            <a href="{{ url('/master/lokasi') }}" class="nav-link {{ request()->is('master/lokasi*') ? 'active' : '' }}">
              <i class="nav-icon bi bi-geo-alt"></i>
              <p>Gudang & Rak Simpan</p>
            </a>
          @else
            <a href="javascript:void(0)" class="nav-link text-body-secondary opacity-50" onclick="window.showToast('Akses Gudang & Rak dibatasi untuk peran {{ $currentRole['name'] }}', 'warning', 'Akses Terbatas')">
              <i class="nav-icon bi bi-geo-alt"></i>
              <p>Gudang & Rak <i class="bi bi-lock-fill float-end text-secondary small"></i></p>
            </a>
          @endif
        </li>

        <li class="nav-item">
          @if(RoleManager::can('master_supplier'))
            <a href="{{ url('/master/supplier') }}" class="nav-link {{ request()->is('master/supplier*') ? 'active' : '' }}">
              <i class="nav-icon bi bi-truck"></i>
              <p>Vendor & Pemasok</p>
            </a>
          @else
            <a href="javascript:void(0)" class="nav-link text-body-secondary opacity-50" onclick="window.showToast('Akses Vendor Supplier dibatasi untuk peran {{ $currentRole['name'] }}', 'warning', 'Akses Terbatas')">
              <i class="nav-icon bi bi-truck"></i>
              <p>Vendor Pemasok <i class="bi bi-lock-fill float-end text-secondary small"></i></p>
            </a>
          @endif
        </li>

        <!-- Header: Operasional Stok -->
        <li class="nav-header">Operasional Stok</li>
        <li class="nav-item">
          @if(RoleManager::can('inbound'))
            <a href="{{ url('/operasional/inbound') }}" class="nav-link {{ request()->is('operasional/inbound*') ? 'active' : '' }}">
              <i class="nav-icon bi bi-box-arrow-in-down text-info"></i>
              <p>
                Penerimaan (Inbound)
                <span class="badge text-bg-info float-end">Aktif</span>
              </p>
            </a>
          @else
            <a href="javascript:void(0)" class="nav-link text-body-secondary opacity-50" onclick="window.showToast('Akses Penerimaan Inbound dibatasi untuk peran {{ $currentRole['name'] }}', 'warning', 'Akses Terbatas')">
              <i class="nav-icon bi bi-box-arrow-in-down"></i>
              <p>Penerimaan (Inbound) <i class="bi bi-lock-fill float-end text-secondary small"></i></p>
            </a>
          @endif
        </li>

        <li class="nav-item">
          @if(RoleManager::can('outbound'))
            <a href="{{ url('/operasional/outbound') }}" class="nav-link {{ request()->is('operasional/outbound*') ? 'active' : '' }}">
              <i class="nav-icon bi bi-box-arrow-up-right text-warning"></i>
              <p>Pengeluaran & Transfer</p>
            </a>
          @else
            <a href="javascript:void(0)" class="nav-link text-body-secondary opacity-50" onclick="window.showToast('Akses Pengeluaran & Transfer dibatasi untuk peran {{ $currentRole['name'] }}', 'warning', 'Akses Terbatas')">
              <i class="nav-icon bi bi-box-arrow-up-right"></i>
              <p>Pengeluaran & Transfer <i class="bi bi-lock-fill float-end text-secondary small"></i></p>
            </a>
          @endif
        </li>

        <li class="nav-item">
          @if(RoleManager::can('opname'))
            <a href="{{ url('/operasional/opname') }}" class="nav-link {{ request()->is('operasional/opname*') ? 'active' : '' }}">
              <i class="nav-icon bi bi-clipboard-check text-success"></i>
              <p>Stock Opname & Selisih</p>
            </a>
          @else
            <a href="javascript:void(0)" class="nav-link text-body-secondary opacity-50" onclick="window.showToast('Akses Stock Opname dibatasi untuk peran {{ $currentRole['name'] }}', 'warning', 'Akses Terbatas')">
              <i class="nav-icon bi bi-clipboard-check"></i>
              <p>Stock Opname <i class="bi bi-lock-fill float-end text-secondary small"></i></p>
            </a>
          @endif
        </li>

        <!-- Header: Laporan & Analitik -->
        <li class="nav-header">Laporan & Audit</li>
        <li class="nav-item">
          @if(RoleManager::can('kartu_stok'))
            <a href="{{ url('/laporan/kartu-stok') }}" class="nav-link {{ request()->is('laporan/kartu-stok*') ? 'active' : '' }}">
              <i class="nav-icon bi bi-journal-text"></i>
              <p>Kartu Stok Digital</p>
            </a>
          @else
            <a href="javascript:void(0)" class="nav-link text-body-secondary opacity-50" onclick="window.showToast('Akses Kartu Stok dibatasi untuk peran {{ $currentRole['name'] }}', 'warning', 'Akses Terbatas')">
              <i class="nav-icon bi bi-journal-text"></i>
              <p>Kartu Stok <i class="bi bi-lock-fill float-end text-secondary small"></i></p>
            </a>
          @endif
        </li>

        <li class="nav-item">
          @if(RoleManager::can('laporan_mutasi'))
            <a href="{{ url('/laporan/mutasi') }}" class="nav-link {{ request()->is('laporan/mutasi*') ? 'active' : '' }}">
              <i class="nav-icon bi bi-graph-up-arrow"></i>
              <p>Laporan Mutasi & Valuasi</p>
            </a>
          @else
            <a href="javascript:void(0)" class="nav-link text-body-secondary opacity-50" onclick="window.showToast('Akses Laporan Valuasi dibatasi untuk peran {{ $currentRole['name'] }}', 'warning', 'Akses Terbatas')">
              <i class="nav-icon bi bi-graph-up-arrow"></i>
              <p>Laporan Mutasi <i class="bi bi-lock-fill float-end text-secondary small"></i></p>
            </a>
          @endif
        </li>

        <!-- Header: Sistem -->
        <li class="nav-header">Sistem</li>
        <li class="nav-item">
          @if(RoleManager::can('pengaturan'))
            <a href="{{ url('/pengaturan') }}" class="nav-link {{ request()->is('pengaturan*') ? 'active' : '' }}">
              <i class="nav-icon bi bi-sliders"></i>
              <p>Pengaturan Prototipe</p>
            </a>
          @else
            <a href="javascript:void(0)" class="nav-link text-body-secondary opacity-50" onclick="window.showToast('Akses Pengaturan dibatasi untuk peran {{ $currentRole['name'] }}', 'warning', 'Akses Terbatas')">
              <i class="nav-icon bi bi-sliders"></i>
              <p>Pengaturan <i class="bi bi-lock-fill float-end text-secondary small"></i></p>
            </a>
          @endif
        </li>
      </ul>
    </nav>
  </div>
</aside>
