@extends('layouts.app')

@section('title', 'Gudang & Lokasi Rak')
@section('page_title', 'Master Data Gudang & Zonasi Rak')
@section('page_description', 'Manajemen multi-lokasi gudang penyimpanan, kapasitas bin, dan pemetaan rak barang')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none">Master Data</a></li>
  <li class="breadcrumb-item active" aria-current="page">Gudang & Rak</li>
@endsection

@section('content')
<!-- Header Summary Row -->
<div class="row g-3 mb-4">
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm p-3 bg-body">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="text-body-secondary small fw-medium">Total Fasilitas Gudang</div>
          <h4 class="fw-bold mb-0 text-primary">{{ count($warehouses) }} Fasilitas</h4>
        </div>
        <div class="p-2 rounded bg-primary-subtle text-primary">
          <i class="bi bi-buildings fs-4"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm p-3 bg-body">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="text-body-secondary small fw-medium">Total Zonasi Rak / Bin</div>
          <h4 class="fw-bold mb-0 text-success">
            {{ array_sum(array_column($warehouses, 'total_bins')) }} Rak Aktif
          </h4>
        </div>
        <div class="p-2 rounded bg-success-subtle text-success">
          <i class="bi bi-grid-3x3-gap-fill fs-4"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm p-3 bg-body">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="text-body-secondary small fw-medium">Rata-Rata Utilisasi</div>
          <h4 class="fw-bold mb-0 text-info">
            {{ round(array_sum(array_column($warehouses, 'capacity_pct')) / count($warehouses)) }}%
          </h4>
        </div>
        <div class="p-2 rounded bg-info-subtle text-info">
          <i class="bi bi-pie-chart-fill fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Warehouse Cards Grid -->
<div class="row g-4 mb-4">
  @foreach($warehouses as $wh)
    @php
      $storedItems = array_filter($items, fn($i) => $i['warehouse_id'] === $wh['id']);
      $storedCount = count($storedItems);
    @endphp
    <div class="col-12 col-lg-4">
      <div class="card border shadow-sm h-100">
        <div class="card-header bg-body py-3 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="p-2 rounded bg-primary-subtle text-primary">
              <i class="bi bi-building fs-5"></i>
            </span>
            <div>
              <h6 class="card-title fw-bold mb-0">{{ $wh['name'] }}</h6>
              <code class="small text-secondary">{{ $wh['code'] }}</code>
            </div>
          </div>
          <span class="badge {{ $wh['capacity_pct'] > 75 ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-success-subtle text-success border border-success-subtle' }} px-2 py-1">
            {{ $wh['type'] }}
          </span>
        </div>

        <div class="card-body">
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center small mb-1">
              <span class="text-body-secondary">Kapasitas Terpakai:</span>
              <span class="fw-bold {{ $wh['capacity_pct'] > 75 ? 'text-danger' : 'text-primary' }}">{{ $wh['capacity_pct'] }}%</span>
            </div>
            <div class="progress" style="height: 8px;">
              <div class="progress-bar {{ $wh['capacity_pct'] > 75 ? 'bg-danger' : 'bg-primary' }}" style="width: {{ $wh['capacity_pct'] }}%;"></div>
            </div>
          </div>

          <div class="p-3 bg-body-tertiary rounded border mb-3 small">
            <div class="row g-2">
              <div class="col-6">
                <span class="text-body-secondary d-block">Kota / Wilayah:</span>
                <span class="fw-semibold text-body"><i class="bi bi-geo-alt me-1 text-danger"></i>{{ $wh['city'] }}</span>
              </div>
              <div class="col-6">
                <span class="text-body-secondary d-block">Kepala Gudang:</span>
                <span class="fw-semibold text-body"><i class="bi bi-person me-1 text-primary"></i>{{ $wh['manager'] }}</span>
              </div>
              <div class="col-6">
                <span class="text-body-secondary d-block">Total Rak Bin:</span>
                <span class="fw-semibold text-body">{{ $wh['total_bins'] }} Slot Rak</span>
              </div>
              <div class="col-6">
                <span class="text-body-secondary d-block">SKU Tersimpan:</span>
                <span class="fw-bold text-success">{{ $storedCount }} Jenis Barang</span>
              </div>
            </div>
          </div>

          <h6 class="fw-bold small text-uppercase text-secondary mb-2">Zonasi Rak Aktif:</h6>
          <div class="d-flex flex-wrap gap-1 mb-3">
            @foreach($wh['racks'] as $rack)
              <span class="badge bg-light text-dark border">
                <i class="bi bi-box-seam me-1 text-primary"></i>{{ $rack }}
              </span>
            @endforeach
          </div>
        </div>

        <div class="card-footer bg-body py-2 d-flex justify-content-between align-items-center">
          <button class="btn btn-outline-secondary btn-sm" onclick='showRackDetails(@json($wh))'>
            <i class="bi bi-grid me-1"></i> Rincian Rak
          </button>
          <button class="btn btn-primary btn-sm" onclick='openAddRackModal(@json($wh))'>
            <i class="bi bi-plus-lg me-1"></i> Tambah Rak
          </button>
        </div>
      </div>
    </div>
  @endforeach
</div>

<!-- Detailed Master Rack Table -->
<div class="card border shadow-sm">
  <div class="card-header bg-body py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h6 class="card-title fw-bold mb-0">
        <i class="bi bi-table text-primary me-2"></i>Daftar Seluruh Zonasi Rak & Penempatan Barang
      </h6>
      <small class="text-body-secondary">Daftar pemetaan rak penyimpanan barang di seluruh gudang</small>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" onclick="window.showToast('Memuat log denah gudang...', 'info', 'Refresh')">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
      </button>
      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#formWarehouseModal">
        <i class="bi bi-plus-lg me-1"></i> Tambah Gudang Baru
      </button>
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="rackTable">
        <thead class="table-light small">
          <tr>
            <th style="width: 50px;" class="text-center">No</th>
            <th>Kode Gudang & Nama Fasilitas</th>
            <th>Nama Rak / Bin</th>
            <th>Barang Yang Disimpan (Contoh)</th>
            <th class="text-center">Kategori</th>
            <th class="text-center">Status Rak</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody class="small">
          @php $rowNo = 1; @endphp
          @foreach($warehouses as $wh)
            @foreach($wh['racks'] as $rack)
              @php
                $sampleItem = current(array_filter($items, fn($i) => $i['warehouse_id'] === $wh['id'] && $i['bin_location'] === $rack));
              @endphp
              <tr>
                <td class="text-center text-secondary">{{ $rowNo++ }}</td>
                <td>
                  <div class="fw-bold">{{ $wh['name'] }}</div>
                  <code class="text-secondary">{{ $wh['code'] }}</code> &bull; <small>{{ $wh['city'] }}</small>
                </td>
                <td>
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-7">
                    <i class="bi bi-geo-alt-fill me-1"></i>{{ $rack }}
                  </span>
                </td>
                <td>
                  @if($sampleItem)
                    <div class="fw-semibold text-body">{{ $sampleItem['name'] }}</div>
                    <code class="text-primary">{{ $sampleItem['sku'] }}</code> &bull; <small>{{ $sampleItem['current_stock'] }} {{ $sampleItem['uom'] }}</small>
                  @else
                    <span class="text-secondary fst-italic">Slot Rak Kosong / Tersedia</span>
                  @endif
                </td>
                <td class="text-center">
                  @if($sampleItem)
                    <span class="badge bg-light text-dark border">{{ $sampleItem['category_name'] }}</span>
                  @else
                    <span class="badge bg-light text-secondary border">Universal</span>
                  @endif
                </td>
                <td class="text-center">
                  @if($sampleItem)
                    <span class="badge bg-success-subtle text-success border border-success-subtle">Terisi</span>
                  @else
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Kosong</span>
                  @endif
                </td>
                <td class="text-end">
                  <button class="btn btn-outline-secondary btn-sm" onclick="window.showToast('Menampilkan detail inventaris rak {{ $rack }} ({{ $wh['name'] }})...', 'info', 'Info Rak')">
                    <i class="bi bi-info-circle"></i>
                  </button>
                </td>
              </tr>
            @endforeach
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal: Rincian Rak Gudang -->
<div class="modal fade" id="rackDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow border-0">
      <div class="modal-header bg-body-tertiary">
        <h5 class="modal-title fw-bold" id="rackDetailModalLabel">
          <i class="bi bi-grid-3x3-gap text-primary me-2"></i>Rincian Zonasi Rak
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="d-flex align-items-center justify-content-between p-3 bg-body-tertiary rounded border mb-4">
          <div>
            <h5 class="fw-bold mb-0" id="modalWhName">-</h5>
            <small class="text-secondary" id="modalWhCity">-</small>
          </div>
          <div class="text-end">
            <span class="badge bg-primary fs-6" id="modalWhCapacity">-</span>
            <small class="text-secondary d-block">Utilisasi Kapasitas</small>
          </div>
        </div>

        <h6 class="fw-bold small text-uppercase text-secondary mb-3">Daftar Slot Rak & Kapasitas Simpan:</h6>
        <div class="row g-3" id="modalRackContainer">
          <!-- Populated by JavaScript -->
        </div>
      </div>
      <div class="modal-footer bg-body-tertiary">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Form Tambah Gudang Baru (Simulasi) -->
<div class="modal fade" id="formWarehouseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow border-0">
      <div class="modal-header bg-body-tertiary">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-building-add text-primary me-2"></i>Tambah Lokasi Fasilitas Gudang
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form onsubmit="handleWarehouseSubmit(event)">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Kode Gudang <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm" id="formWhCode" placeholder="misal: WH-BDG-04" required />
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Fasilitas Gudang <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm" id="formWhName" placeholder="misal: Gudang Distribusi Bandung" required />
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Jenis / Tipe Gudang</label>
            <select class="form-select form-select-sm" id="formWhType">
              <option value="Pusat Distribusi">Pusat Distribusi</option>
              <option value="Transit & Buffer">Transit & Buffer</option>
              <option value="Karantina & QC">Karantina & QC</option>
              <option value="Gudang Retur">Gudang Retur</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Kota / Lokasi <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm" id="formWhCity" placeholder="Kota / Alamat" required />
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Kepala / Manager Gudang</label>
            <input type="text" class="form-control form-control-sm" id="formWhManager" placeholder="Nama Penanggung Jawab" />
          </div>
        </div>
        <div class="modal-footer bg-body-tertiary">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm px-4">
            <i class="bi bi-save me-1"></i> Simpan Gudang
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function showRackDetails(wh) {
    document.getElementById('modalWhName').textContent = wh.name + ' (' + wh.code + ')';
    document.getElementById('modalWhCity').textContent = wh.city + ' • Manager: ' + wh.manager;
    document.getElementById('modalWhCapacity').textContent = wh.capacity_pct + '% Terisi';

    const container = document.getElementById('modalRackContainer');
    container.innerHTML = '';

    wh.racks.forEach(rack => {
      const col = document.createElement('div');
      col.className = 'col-6 col-md-4';
      col.innerHTML = `
        <div class="p-3 border rounded bg-body text-center shadow-sm">
          <i class="bi bi-box-seam fs-3 text-primary mb-1 d-block"></i>
          <span class="fw-bold d-block small">${rack}</span>
          <span class="badge bg-success-subtle text-success border border-success-subtle mt-1" style="font-size: 0.7rem;">Aktif Simpan</span>
        </div>
      `;
      container.appendChild(col);
    });

    const modal = new bootstrap.Modal(document.getElementById('rackDetailModal'));
    modal.show();
  }

  function openAddRackModal(wh) {
    window.showToast('Menambahkan slot rak baru pada ' + wh.name + '...', 'info', 'Tambah Rak');
  }

  function handleWarehouseSubmit(e) {
    e.preventDefault();
    const name = document.getElementById('formWhName').value;
    const code = document.getElementById('formWhCode').value;

    bootstrap.Modal.getInstance(document.getElementById('formWarehouseModal')).hide();
    window.showToast('Fasilitas Gudang [' + code + ' - ' + name + '] berhasil ditambahkan ke sistem.', 'success', 'Simpan Gudang');
  }
</script>
@endpush
