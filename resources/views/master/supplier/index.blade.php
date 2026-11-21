@extends('layouts.app')

@section('title', 'Vendor & Mitra Pemasok')
@section('page_title', 'Master Data Vendor & Pemasok')
@section('page_description', 'Direktori pemasok resmi, kontak narahubung purchasing, dan evaluasi performa vendor')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none">Master Data</a></li>
  <li class="breadcrumb-item active" aria-current="page">Vendor & Pemasok</li>
@endsection

@section('content')
<!-- Supplier Summary Row -->
<div class="row g-3 mb-4">
  <div class="col-12 col-md-3">
    <div class="card border-0 shadow-sm p-3 bg-body">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="text-body-secondary small fw-medium">Total Vendor Terdaftar</div>
          <h4 class="fw-bold mb-0 text-primary">{{ count($suppliers) }} Mitra</h4>
        </div>
        <div class="p-2 rounded bg-primary-subtle text-primary">
          <i class="bi bi-truck fs-4"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-3">
    <div class="card border-0 shadow-sm p-3 bg-body">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="text-body-secondary small fw-medium">Status Kerjasama</div>
          <h4 class="fw-bold mb-0 text-success">100% Aktif</h4>
        </div>
        <div class="p-2 rounded bg-success-subtle text-success">
          <i class="bi bi-patch-check-fill fs-4"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-3">
    <div class="card border-0 shadow-sm p-3 bg-body">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="text-body-secondary small fw-medium">Rata-Rata Rating Vendor</div>
          <h4 class="fw-bold mb-0 text-warning">
            <i class="bi bi-star-fill text-warning me-1"></i>
            {{ number_format(array_sum(array_column($suppliers, 'rating')) / count($suppliers), 1) }}
          </h4>
        </div>
        <div class="p-2 rounded bg-warning-subtle text-warning">
          <i class="bi bi-star-half fs-4"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-3">
    <div class="card border-0 shadow-sm p-3 bg-body">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="text-body-secondary small fw-medium">PO Bulan Berjalan</div>
          <h4 class="fw-bold mb-0 text-info">12 Order</h4>
        </div>
        <div class="p-2 rounded bg-info-subtle text-info">
          <i class="bi bi-receipt fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Supplier Table Card -->
<div class="card border shadow-sm">
  <div class="card-header bg-body py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h6 class="card-title fw-bold mb-0">
        <i class="bi bi-person-lines-fill text-primary me-2"></i>Direktori Vendor Resmi Perusahaan
      </h6>
      <small class="text-body-secondary">Daftar pemasok terverifikasi untuk pengadaan bahan baku dan peralatan</small>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" onclick="window.showToast('Mengekspor daftar vendor ke format Excel...', 'info', 'Ekspor Data')">
        <i class="bi bi-file-earmark-excel me-1"></i> Ekspor
      </button>
      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#formSupplierModal">
        <i class="bi bi-plus-lg me-1"></i> Tambah Vendor Baru
      </button>
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="supplierTable">
        <thead class="table-light small">
          <tr>
            <th style="width: 50px;" class="text-center">No</th>
            <th>Kode & Nama Vendor</th>
            <th>Kategori Pasokan Utama</th>
            <th>Narahubung (PIC) & Kontak</th>
            <th>Kota Domisili</th>
            <th class="text-center">Rating Kualitas</th>
            <th class="text-center">Status</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody class="small">
          @foreach($suppliers as $idx => $sup)
            <tr>
              <td class="text-center text-secondary fw-semibold">{{ $idx + 1 }}</td>
              <td>
                <div class="fw-bold text-body">{{ $sup['name'] }}</div>
                <code class="text-primary">{{ $sup['id'] }}</code>
              </td>
              <td>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                  {{ $sup['category'] }}
                </span>
              </td>
              <td>
                <div class="fw-semibold text-body">{{ $sup['contact'] }}</div>
                <small class="text-secondary"><i class="bi bi-envelope me-1"></i>{{ $sup['email'] }}</small>
              </td>
              <td>
                <i class="bi bi-geo-alt text-danger me-1"></i>{{ $sup['city'] }}
              </td>
              <td class="text-center">
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-bold px-2 py-1">
                  <i class="bi bi-star-fill text-warning me-1"></i>{{ $sup['rating'] }} / 5.0
                </span>
              </td>
              <td class="text-center">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                  <i class="bi bi-check-circle-fill me-1"></i> Aktif
                </span>
              </td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <button type="button" class="btn btn-outline-secondary" title="Detail Profil" onclick='showSupplierDetail(@json($sup))'>
                    <i class="bi bi-eye"></i>
                  </button>
                  <button type="button" class="btn btn-outline-primary" title="Ubah Vendor" onclick='openEditSupplierModal(@json($sup))'>
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button type="button" class="btn btn-outline-success" title="Order Barang" onclick="window.showToast('Membuat Purchase Order untuk {{ $sup['name'] }}...', 'info', 'Buat PO')">
                    <i class="bi bi-cart-plus"></i>
                  </button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal: Detail Supplier -->
<div class="modal fade" id="detailSupplierModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow border-0">
      <div class="modal-header bg-body-tertiary">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-building-check text-primary me-2"></i>Profil Mitra Vendor
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="text-center mb-4">
          <div class="d-inline-flex p-3 rounded-circle bg-primary-subtle text-primary mb-2">
            <i class="bi bi-truck fs-1"></i>
          </div>
          <h5 class="fw-bold mb-1" id="modalSupName">-</h5>
          <code class="text-primary fs-6 fw-bold" id="modalSupId">-</code>
          <div class="mt-2">
            <span class="badge bg-success-subtle text-success border border-success-subtle">Rekanan Terverifikasi ISO-9001</span>
          </div>
        </div>

        <div class="p-3 bg-body-tertiary rounded border mb-3 small">
          <div class="row g-2">
            <div class="col-6">
              <span class="text-body-secondary d-block">Bidang Pasokan:</span>
              <span class="fw-semibold text-body" id="modalSupCategory">-</span>
            </div>
            <div class="col-6">
              <span class="text-body-secondary d-block">Kota Domisili:</span>
              <span class="fw-semibold text-body" id="modalSupCity">-</span>
            </div>
            <div class="col-6">
              <span class="text-body-secondary d-block">Narahubung (PIC):</span>
              <span class="fw-semibold text-body" id="modalSupContact">-</span>
            </div>
            <div class="col-6">
              <span class="text-body-secondary d-block">Rating Evaluasi:</span>
              <span class="fw-bold text-warning" id="modalSupRating">-</span>
            </div>
            <div class="col-12 mt-2 pt-2 border-top">
              <span class="text-body-secondary d-block">Email Resmi:</span>
              <span class="fw-semibold text-primary" id="modalSupEmail">-</span>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-body-tertiary">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="triggerPoFromModal()">
          <i class="bi bi-cart-plus me-1"></i> Buat PO Pengadaan
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Form Tambah / Edit Supplier (Simulasi) -->
<div class="modal fade" id="formSupplierModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow border-0">
      <div class="modal-header bg-body-tertiary">
        <h5 class="modal-title fw-bold" id="formSupplierModalLabel">
          <i class="bi bi-plus-circle-fill text-primary me-2"></i>Tambah Mitra Vendor Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form onsubmit="handleSupplierSubmit(event)">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Kode Vendor <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm" id="formSupId" placeholder="misal: SUP-006" required />
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Perusahaan / Vendor <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm" id="formSupName" placeholder="misal: PT Prima Logistik Jaya" required />
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Kategori Pasokan Utama <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm" id="formSupCategory" placeholder="misal: Bahan Baku & Logam" required />
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Narahubung (PIC & No. HP) <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm" id="formSupContact" placeholder="misal: Ir. Hendra (0812-9988-7766)" required />
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Email Resmi Perusahaan</label>
            <input type="email" class="form-control form-control-sm" id="formSupEmail" placeholder="sales@perusahaan.com" />
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Kota Domisili</label>
            <input type="text" class="form-control form-control-sm" id="formSupCity" placeholder="Kota" />
          </div>
        </div>
        <div class="modal-footer bg-body-tertiary">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm px-4">
            <i class="bi bi-save me-1"></i> Simpan Mitra
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let activeSelectedSupplier = null;

  function showSupplierDetail(sup) {
    activeSelectedSupplier = sup;
    document.getElementById('modalSupName').textContent = sup.name;
    document.getElementById('modalSupId').textContent = sup.id;
    document.getElementById('modalSupCategory').textContent = sup.category;
    document.getElementById('modalSupCity').textContent = sup.city;
    document.getElementById('modalSupContact').textContent = sup.contact;
    document.getElementById('modalSupEmail').textContent = sup.email;
    document.getElementById('modalSupRating').textContent = sup.rating + ' / 5.0 (Sangat Baik)';

    const modal = new bootstrap.Modal(document.getElementById('detailSupplierModal'));
    modal.show();
  }

  function triggerPoFromModal() {
    if (activeSelectedSupplier) {
      bootstrap.Modal.getInstance(document.getElementById('detailSupplierModal')).hide();
      window.showToast('Pengajuan PO pembelian untuk ' + activeSelectedSupplier.name + ' berhasil disiapkan.', 'success', 'Pengadaan PO');
    }
  }

  function openEditSupplierModal(sup) {
    document.getElementById('formSupplierModalLabel').innerHTML = '<i class="bi bi-pencil-square text-primary me-2"></i>Ubah Data Vendor (' + sup.id + ')';
    document.getElementById('formSupId').value = sup.id;
    document.getElementById('formSupName').value = sup.name;
    document.getElementById('formSupCategory').value = sup.category;
    document.getElementById('formSupContact').value = sup.contact;
    document.getElementById('formSupEmail').value = sup.email;
    document.getElementById('formSupCity').value = sup.city;

    const modal = new bootstrap.Modal(document.getElementById('formSupplierModal'));
    modal.show();
  }

  function handleSupplierSubmit(e) {
    e.preventDefault();
    const id = document.getElementById('formSupId').value;
    const name = document.getElementById('formSupName').value;

    bootstrap.Modal.getInstance(document.getElementById('formSupplierModal')).hide();
    window.showToast('Data vendor mitra [' + id + ' - ' + name + '] berhasil disimpan ke master.', 'success', 'Simpan Vendor');
  }
</script>
@endpush
