@extends('layouts.app')

@section('title', 'Dashboard Inventaris')
@section('page_title', 'Dashboard Ringkasan Inventaris')
@section('page_description', 'Pemantauan persediaan stok barang, pergerakan gudang, dan aktivitas transaksi')

@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<!-- Interactive Role Context Banner -->
<div class="card border-{{ $currentRole['badge'] }}-subtle shadow-sm mb-4 bg-body">
  <div class="card-body p-4">
    <div class="row align-items-center g-3">
      <div class="col-md-8 d-flex align-items-center gap-3">
        <img src="{{ asset('theme/AdminLTE-4/dist/assets/img/' . $currentRole['avatar']) }}" alt="Avatar" class="rounded-circle shadow border border-2 border-{{ $currentRole['badge'] }}" width="64" height="64" />
        <div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
            <h5 class="fw-bold mb-0">{{ $currentRole['user_name'] }}</h5>
            <span class="badge bg-{{ $currentRole['badge'] }} text-white">
              <i class="bi {{ $currentRole['icon'] }} me-1"></i> {{ $currentRole['name'] }}
            </span>
            <span class="badge bg-success-subtle text-success border border-success-subtle">
              <i class="bi bi-shield-check me-1"></i> Otorisasi Aktif
            </span>
          </div>
          <p class="text-body-secondary small mb-0">
            {{ $currentRole['description'] }}
          </p>
        </div>
      </div>
      <div class="col-md-4 text-md-end">
        <div class="btn-group shadow-sm">
          <button type="button" class="btn btn-outline-{{ $currentRole['badge'] }} btn-sm dropdown-toggle px-3 py-2 fw-medium" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-arrow-repeat me-1"></i> Ganti Peran (Role)
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li class="dropdown-header fw-bold">Pilih Role Simulasi:</li>
            @foreach($allRoles as $key => $r)
              <li>
                <a class="dropdown-item d-flex align-items-center justify-content-between py-2 {{ $currentRole['id'] === $key ? 'active fw-bold' : '' }}" href="{{ url('/switch-role/' . $key) }}">
                  <span><i class="bi {{ $r['icon'] }} text-{{ $r['badge'] }} me-2"></i> {{ $r['name'] }}</span>
                  @if($currentRole['id'] === $key)
                    <i class="bi bi-check-lg ms-2"></i>
                  @endif
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 4 KPI Stat Cards Placeholder -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body">
      <div class="card-body d-flex align-items-center">
        <div class="d-inline-flex p-3 rounded-3 bg-primary-subtle text-primary me-3">
          <i class="bi bi-boxes fs-3"></i>
        </div>
        <div>
          <div class="text-body-secondary small fw-medium">Total SKU Barang</div>
          <h3 class="fw-bold mb-0">{{ $inventoryStats['total_sku'] }} <span class="fs-6 text-muted fw-normal">Item</span></h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body">
      <div class="card-body d-flex align-items-center">
        <div class="d-inline-flex p-3 rounded-3 bg-success-subtle text-success me-3">
          <i class="bi bi-cash-stack fs-3"></i>
        </div>
        <div>
          <div class="text-body-secondary small fw-medium">Total Valuasi Aset</div>
          <h3 class="fw-bold mb-0 fs-5 text-truncate" title="{{ $inventoryStats['total_valuation_formatted'] }}">{{ $inventoryStats['total_valuation_formatted'] }}</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body">
      <div class="card-body d-flex align-items-center">
        <div class="d-inline-flex p-3 rounded-3 bg-warning-subtle text-warning me-3">
          <i class="bi bi-exclamation-triangle fs-3"></i>
        </div>
        <div>
          <div class="text-body-secondary small fw-medium">Peringatan Stok Menipis</div>
          <h3 class="fw-bold mb-0 text-warning">{{ $inventoryStats['alert_count'] }} <span class="fs-6 text-muted fw-normal">SKU</span></h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body">
      <div class="card-body d-flex align-items-center">
        <div class="d-inline-flex p-3 rounded-3 bg-info-subtle text-info me-3">
          <i class="bi bi-arrow-left-right fs-3"></i>
        </div>
        <div>
          <div class="text-body-secondary small fw-medium">Mutasi Transaksi</div>
          <h3 class="fw-bold mb-0">{{ number_format($inventoryStats['monthly_movements']) }} <span class="fs-6 text-muted fw-normal">Qty</span></h3>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Role Permission Matrix Verification Card -->
<div class="card border shadow-sm mb-4">
  <div class="card-header bg-body py-3 d-flex align-items-center justify-content-between">
    <h6 class="card-title fw-bold mb-0">
      <i class="bi bi-shield-lock-fill text-{{ $currentRole['badge'] }} me-2"></i>Matriks Hak Akses Modul — {{ $currentRole['name'] }}
    </h6>
    <span class="badge bg-{{ $currentRole['badge'] }}-subtle text-{{ $currentRole['badge'] }} border">T2 Engine Verified</span>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light small">
          <tr>
            <th>Modul Sistem</th>
            <th>Kategori</th>
            <th>Hak Akses Peran Saat Ini</th>
            <th>Catatan Kebijakan</th>
          </tr>
        </thead>
        <tbody class="small">
          @php
            $modules = [
              ['key' => 'dashboard', 'name' => 'Dashboard Utama', 'cat' => 'Utama', 'note' => 'Ringkasan KPI & grafik pergerakan stok'],
              ['key' => 'master_barang', 'name' => 'Katalog Barang & SKU', 'cat' => 'Master Data', 'note' => 'Informasi spesifikasi, UOM, dan batas minimum stok'],
              ['key' => 'master_lokasi', 'name' => 'Gudang & Rak Simpan', 'cat' => 'Master Data', 'note' => 'Pemetaan lokasi fisik dan zonasi rak'],
              ['key' => 'master_supplier', 'name' => 'Vendor & Pemasok', 'cat' => 'Master Data', 'note' => 'Daftar vendor resmi & kontak purchasing'],
              ['key' => 'inbound', 'name' => 'Penerimaan Barang (Inbound)', 'cat' => 'Operasional', 'note' => 'Input dan verifikasi Goods Receipt (GR)'],
              ['key' => 'outbound', 'name' => 'Pengeluaran & Transfer', 'cat' => 'Operasional', 'note' => 'Distribusi barang dan mutasi antar gudang'],
              ['key' => 'opname', 'name' => 'Stock Opname & Selisih', 'cat' => 'Operasional', 'note' => 'Pencatatan selisih hitung fisik & review write-off'],
              ['key' => 'kartu_stok', 'name' => 'Kartu Stok Digital', 'cat' => 'Laporan', 'note' => 'Riwayat mutasi kronologis per-SKU'],
              ['key' => 'laporan_mutasi', 'name' => 'Laporan Mutasi & Valuasi', 'cat' => 'Laporan', 'note' => 'Analisis total nilai persediaan & pergerakan barang'],
              ['key' => 'pengaturan', 'name' => 'Pengaturan Prototipe', 'cat' => 'Sistem', 'note' => 'Konfigurasi master & audit trail demo'],
            ];
          @endphp

          @foreach($modules as $mod)
            @php $hasPerm = in_array($mod['key'], $currentRole['permissions']); @endphp
            <tr>
              <td class="fw-semibold">{{ $mod['name'] }}</td>
              <td><span class="badge bg-light text-dark border">{{ $mod['cat'] }}</span></td>
              <td>
                @if($hasPerm)
                  <span class="badge bg-success-subtle text-success border border-success-subtle">
                    <i class="bi bi-check-circle-fill me-1"></i> Diizinkan (Akses Penuh)
                  </span>
                @else
                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                    <i class="bi bi-x-circle-fill me-1"></i> Dibatasi (Terkunci)
                  </span>
                @endif
              </td>
              <td class="text-body-secondary">{{ $mod['note'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
