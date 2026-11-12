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
    <!-- User Quick Info -->
    <div class="user-panel py-3 px-3 d-flex align-items-center border-bottom border-secondary-subtle">
      <div class="image">
        <img src="{{ asset('theme/AdminLTE-4/dist/assets/img/avatar.png') }}" class="rounded-circle shadow-sm" width="36" height="36" alt="User Image" />
      </div>
      <div class="info ms-3">
        <div class="d-block text-white fw-semibold small">Budi Santoso</div>
        <small class="text-success d-flex align-items-center gap-1" style="font-size: 0.75rem;">
          <span class="p-1 bg-success rounded-circle"></span> Online (Demo)
        </small>
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
          <a href="{{ url('/master/barang') }}" class="nav-link {{ request()->is('master/barang*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-boxes"></i>
            <p>Katalog Barang & SKU</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/master/lokasi') }}" class="nav-link {{ request()->is('master/lokasi*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-geo-alt"></i>
            <p>Gudang & Rak Simpan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/master/supplier') }}" class="nav-link {{ request()->is('master/supplier*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-truck"></i>
            <p>Vendor & Pemasok</p>
          </a>
        </li>

        <!-- Header: Operasional Stok -->
        <li class="nav-header">Operasional Stok</li>
        <li class="nav-item">
          <a href="{{ url('/operasional/inbound') }}" class="nav-link {{ request()->is('operasional/inbound*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-box-arrow-in-down"></i>
            <p>
              Penerimaan (Inbound)
              <span class="badge text-bg-info float-end">Baru</span>
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/operasional/outbound') }}" class="nav-link {{ request()->is('operasional/outbound*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-box-arrow-up-right"></i>
            <p>Pengeluaran & Transfer</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/operasional/opname') }}" class="nav-link {{ request()->is('operasional/opname*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-clipboard-check"></i>
            <p>Stock Opname & Selisih</p>
          </a>
        </li>

        <!-- Header: Laporan & Analitik -->
        <li class="nav-header">Laporan & Audit</li>
        <li class="nav-item">
          <a href="{{ url('/laporan/kartu-stok') }}" class="nav-link {{ request()->is('laporan/kartu-stok*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-journal-text"></i>
            <p>Kartu Stok Digital</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/laporan/mutasi') }}" class="nav-link {{ request()->is('laporan/mutasi*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-graph-up-arrow"></i>
            <p>Laporan Mutasi & Valuasi</p>
          </a>
        </li>

        <!-- Header: Sistem -->
        <li class="nav-header">Sistem</li>
        <li class="nav-item">
          <a href="{{ url('/pengaturan') }}" class="nav-link {{ request()->is('pengaturan*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-sliders"></i>
            <p>Pengaturan Prototipe</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/') }}" class="nav-link">
            <i class="nav-icon bi bi-box-arrow-left"></i>
            <p>Beranda Landing Page</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
