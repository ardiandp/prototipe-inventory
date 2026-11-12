@extends('layouts.app')

@section('title', 'Dashboard Inventaris')
@section('page_title', 'Dashboard Ringkasan Inventaris')
@section('page_description', 'Pemantauan persediaan stok barang, pergerakan gudang, dan aktivitas transaksi')

@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<!-- App Shell Verification Banner -->
<div class="alert alert-success border-success-subtle shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4" role="alert">
  <div class="d-flex align-items-center gap-2">
    <i class="bi bi-patch-check-fill fs-4 text-success"></i>
    <div>
      <h6 class="alert-heading mb-0 fw-bold">Task T1 — App Shell AdminLTE-4 Berhasil Diimplementasikan!</h6>
      <p class="mb-0 small text-success-emphasis">
        Shell modular AdminLTE-4 (Topbar, Sidebar responsif, Header breadcrumb, Dark/Light mode, & Toast feedback) siap digunakan.
      </p>
    </div>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-success btn-sm px-3 shadow-sm" onclick="window.showToast('App Shell AdminLTE-4 berhasil diuji!', 'success', 'Pengujian Shell')">
      <i class="bi bi-bell-fill me-1"></i> Uji Toast
    </button>
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
          <h3 class="fw-bold mb-0">128 <span class="fs-6 text-muted fw-normal">Item</span></h3>
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
          <h3 class="fw-bold mb-0">Rp 482.5M</h3>
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
          <h3 class="fw-bold mb-0 text-warning">6 <span class="fs-6 text-muted fw-normal">SKU</span></h3>
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
          <div class="text-body-secondary small fw-medium">Mutasi Bulan Ini</div>
          <h3 class="fw-bold mb-0">1,420 <span class="fs-6 text-muted fw-normal">Qty</span></h3>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Layout Demo Card -->
<div class="card border shadow-sm mb-4">
  <div class="card-header bg-body py-3 d-flex align-items-center justify-content-between">
    <h6 class="card-title fw-bold mb-0">
      <i class="bi bi-window-sidebar me-2 text-primary"></i>Fitur App Shell Terpasang
    </h6>
    <span class="badge bg-primary">AdminLTE 4.0 Native</span>
  </div>
  <div class="card-body">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="p-3 border rounded bg-body-tertiary h-100">
          <h6 class="fw-bold"><i class="bi bi-layout-sidebar-inset text-primary me-2"></i>Sidebar & Push Menu</h6>
          <p class="small text-body-secondary mb-0">
            Dapat diperkecil (*collapse*) dan diperluas melalui tombol toggle di topbar. Responsif di layar tablet dan ponsel (*drawer mode*).
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 border rounded bg-body-tertiary h-100">
          <h6 class="fw-bold"><i class="bi bi-circle-half text-info me-2"></i>Dark & Light Mode</h6>
          <p class="small text-body-secondary mb-0">
            Mendukung tema Gelap, Terang, dan Otomatis (sinkron OS) yang tersimpan permanen di <code>localStorage</code> tanpa *page reload*.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 border rounded bg-body-tertiary h-100">
          <h6 class="fw-bold"><i class="bi bi-bell-fill text-warning me-2"></i>Toast Notification Helper</h6>
          <p class="small text-body-secondary mb-0">
            Fungsi global <code>window.showToast(msg, type, title)</code> siap dipanggil dari aksi interaktif di modul master data dan operasional.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
