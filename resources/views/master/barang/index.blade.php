@extends('layouts.app')

@section('title', 'Katalog Barang & SKU')
@section('page_title', 'Master Data Barang & SKU')
@section('page_description', 'Manajemen katalog produk, spesifikasi teknis, harga, dan penetapan batas stok minimum')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none">Master Data</a></li>
  <li class="breadcrumb-item active" aria-current="page">Barang & SKU</li>
@endsection

@section('content')
<!-- Filter & Summary Bar -->
<div class="row g-3 mb-4">
  <!-- Status Quick Filter Cards -->
  <div class="col-12 col-md-3">
    <div class="card border-0 shadow-sm p-3 bg-body cursor-pointer quick-filter-card" onclick="filterByStatus('')">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="text-body-secondary small fw-medium">Total Katalog SKU</div>
          <h4 class="fw-bold mb-0 text-primary">{{ count($items) }}</h4>
        </div>
        <div class="p-2 rounded bg-primary-subtle text-primary">
          <i class="bi bi-boxes fs-4"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-3">
    <div class="card border-0 shadow-sm p-3 bg-body cursor-pointer quick-filter-card" onclick="filterByStatus('Aman')">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="text-body-secondary small fw-medium">Stok Aman</div>
          <h4 class="fw-bold mb-0 text-success">
            {{ count(array_filter($items, fn($i) => $i['status'] === 'Aman')) }}
          </h4>
        </div>
        <div class="p-2 rounded bg-success-subtle text-success">
          <i class="bi bi-check-circle-fill fs-4"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-3">
    <div class="card border-0 shadow-sm p-3 bg-body cursor-pointer quick-filter-card" onclick="filterByStatus('Menipis')">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="text-body-secondary small fw-medium">Stok Menipis</div>
          <h4 class="fw-bold mb-0 text-warning">
            {{ count(array_filter($items, fn($i) => $i['status'] === 'Menipis')) }}
          </h4>
        </div>
        <div class="p-2 rounded bg-warning-subtle text-warning">
          <i class="bi bi-exclamation-triangle-fill fs-4"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-3">
    <div class="card border-0 shadow-sm p-3 bg-body cursor-pointer quick-filter-card" onclick="filterByStatus('Habis')">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="text-body-secondary small fw-medium">Stok Habis (Kosong)</div>
          <h4 class="fw-bold mb-0 text-danger">
            {{ count(array_filter($items, fn($i) => $i['status'] === 'Habis')) }}
          </h4>
        </div>
        <div class="p-2 rounded bg-danger-subtle text-danger">
          <i class="bi bi-x-circle-fill fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Table Card -->
<div class="card border shadow-sm">
  <div class="card-header bg-body py-3">
    <div class="row align-items-center g-3">
      <!-- Search Box -->
      <div class="col-12 col-md-4">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-body-tertiary border-end-0"><i class="bi bi-search text-secondary"></i></span>
          <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari nama barang atau SKU..." onkeyup="applyFilters()" />
        </div>
      </div>

      <!-- Category Filter -->
      <div class="col-6 col-md-3">
        <select id="categoryFilter" class="form-select form-select-sm" onchange="applyFilters()">
          <option value="">Semua Kategori</option>
          @foreach($categories as $cat)
            <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
          @endforeach
        </select>
      </div>

      <!-- Status Filter -->
      <div class="col-6 col-md-2">
        <select id="statusFilter" class="form-select form-select-sm" onchange="applyFilters()">
          <option value="">Semua Status</option>
          <option value="Aman">Stok Aman</option>
          <option value="Menipis">Stok Menipis</option>
          <option value="Habis">Stok Habis</option>
        </select>
      </div>

      <!-- Action Buttons -->
      <div class="col-12 col-md-3 text-md-end d-flex gap-2 justify-content-md-end">
        <button class="btn btn-outline-secondary btn-sm" onclick="exportDataSimulasi()">
          <i class="bi bi-file-earmark-excel me-1"></i> Ekspor
        </button>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#formItemModal" onclick="openAddModal()">
          <i class="bi bi-plus-lg me-1"></i> Tambah SKU
        </button>
      </div>
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="itemsTable">
        <thead class="table-light small">
          <tr>
            <th style="width: 50px;" class="text-center">No</th>
            <th>Kode SKU & Nama Barang</th>
            <th>Kategori</th>
            <th>Lokasi Simpan & Rak</th>
            <th class="text-end">Harga Beli / Jual</th>
            <th class="text-center">Stok / Batas Min</th>
            <th class="text-center">Status</th>
            <th class="text-end" style="width: 140px;">Aksi</th>
          </tr>
        </thead>
        <tbody class="small">
          @foreach($items as $idx => $item)
            <tr class="item-row" data-sku="{{ strtolower($item['sku']) }}" data-name="{{ strtolower($item['name']) }}" data-category="{{ $item['category_id'] }}" data-status="{{ $item['status'] }}">
              <td class="text-center text-secondary fw-semibold">{{ $idx + 1 }}</td>
              <td>
                <div class="fw-bold text-body item-name">{{ $item['name'] }}</div>
                <code class="text-primary">{{ $item['sku'] }}</code> &bull; <span class="text-secondary small">{{ $item['uom'] }}</span>
              </td>
              <td>
                <span class="badge bg-light text-dark border">
                  {{ $item['category_name'] }}
                </span>
              </td>
              <td>
                <div class="fw-semibold"><i class="bi bi-geo-alt text-danger me-1"></i>{{ $item['bin_location'] }}</div>
                <small class="text-secondary">{{ $item['warehouse_name'] }}</small>
              </td>
              <td class="text-end">
                <div class="fw-bold text-body">Rp {{ number_format($item['buy_price'], 0, ',', '.') }}</div>
                <small class="text-success">Jual: Rp {{ number_format($item['sell_price'], 0, ',', '.') }}</small>
              </td>
              <td class="text-center">
                <div class="fw-bold fs-6 {{ $item['current_stock'] == 0 ? 'text-danger' : ($item['current_stock'] <= $item['min_stock'] ? 'text-warning' : 'text-success') }}">
                  {{ $item['current_stock'] }} {{ $item['uom'] }}
                </div>
                <small class="text-secondary">Min: {{ $item['min_stock'] }} | Max: {{ $item['max_stock'] }}</small>
              </td>
              <td class="text-center">
                @if($item['status'] === 'Aman')
                  <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                    <i class="bi bi-check-circle-fill me-1"></i> Aman
                  </span>
                @elseif($item['status'] === 'Menipis')
                  <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Menipis
                  </span>
                @else
                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                    <i class="bi bi-x-circle-fill me-1"></i> Habis
                  </span>
                @endif
              </td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <button type="button" class="btn btn-outline-secondary" title="Detail Spesifikasi" onclick='showItemDetail(@json($item))'>
                    <i class="bi bi-eye"></i>
                  </button>
                  <button type="button" class="btn btn-outline-primary" title="Ubah Data" onclick='showEditModal(@json($item))'>
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button type="button" class="btn btn-outline-success" title="Kartu Stok" onclick="window.showToast('Membuka riwayat mutasi untuk {{ $item['sku'] }}...', 'info', 'Kartu Stok')">
                    <i class="bi bi-journal-text"></i>
                  </button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Empty State for Filter -->
    <div id="noMatchAlert" class="text-center py-5 d-none">
      <i class="bi bi-search fs-1 text-secondary opacity-50 d-block mb-2"></i>
      <h6 class="fw-bold">Tidak ada barang yang sesuai</h6>
      <p class="text-secondary small mb-0">Coba gunakan kata kunci pencarian atau filter yang berbeda.</p>
    </div>
  </div>

  <div class="card-footer bg-body py-3 d-flex align-items-center justify-content-between flex-wrap gap-2 text-secondary small">
    <div>
      Menampilkan <span id="visibleCount" class="fw-bold text-body">{{ count($items) }}</span> dari <strong>{{ count($items) }}</strong> SKU terdaftar
    </div>
    <div class="d-flex align-items-center gap-1">
      <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Halaman 1 dari 1 (Semua ditampilkan)</span>
    </div>
  </div>
</div>

<!-- Modal: Detail Spesifikasi Barang -->
<div class="modal fade" id="detailItemModal" tabindex="-1" aria-labelledby="detailItemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow border-0">
      <div class="modal-header bg-body-tertiary">
        <h5 class="modal-title fw-bold" id="detailItemModalLabel">
          <i class="bi bi-box-seam text-primary me-2"></i>Detail Spesifikasi SKU
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-4">
          <div class="col-md-7">
            <h4 class="fw-bold text-body mb-1" id="modalItemName">-</h4>
            <div class="d-flex align-items-center gap-2 mb-3">
              <code class="fs-6 text-primary fw-bold" id="modalItemSku">-</code>
              <span class="badge bg-light text-dark border" id="modalItemCategory">-</span>
              <span class="badge" id="modalItemStatus">-</span>
            </div>

            <div class="p-3 bg-body-tertiary rounded border mb-3">
              <div class="row g-2 small">
                <div class="col-6">
                  <div class="text-body-secondary">Satuan (UOM):</div>
                  <div class="fw-bold text-body" id="modalItemUom">-</div>
                </div>
                <div class="col-6">
                  <div class="text-body-secondary">Gudang Utama:</div>
                  <div class="fw-bold text-body" id="modalItemWarehouse">-</div>
                </div>
                <div class="col-6">
                  <div class="text-body-secondary">Lokasi Rak Simpan:</div>
                  <div class="fw-bold text-danger" id="modalItemBin">-</div>
                </div>
                <div class="col-6">
                  <div class="text-body-secondary">Valuasi Stok Tersedia:</div>
                  <div class="fw-bold text-success" id="modalItemValuation">-</div>
                </div>
              </div>
            </div>

            <h6 class="fw-bold small text-uppercase text-secondary mb-2">Parameter Pengamanan Stok:</h6>
            <div class="d-flex align-items-center justify-content-between p-2 px-3 border rounded bg-body mb-2 small">
              <span>Batas Minimum (Safety Stock):</span>
              <span class="fw-bold text-danger" id="modalItemMinStock">-</span>
            </div>
            <div class="d-flex align-items-center justify-content-between p-2 px-3 border rounded bg-body small">
              <span>Kapasitas Maksimum Rak:</span>
              <span class="fw-bold text-primary" id="modalItemMaxStock">-</span>
            </div>
          </div>

          <div class="col-md-5 border-start-md">
            <div class="card bg-body-tertiary border-0 p-3 h-100 d-flex flex-column justify-content-between">
              <div>
                <h6 class="fw-bold mb-3"><i class="bi bi-tag-fill text-success me-2"></i>Harga & Finansial</h6>
                <div class="mb-3">
                  <small class="text-body-secondary d-block">Harga Beli Terakhir (HPP):</small>
                  <span class="fs-5 fw-bold text-body" id="modalItemBuyPrice">Rp 0</span>
                </div>
                <div class="mb-3">
                  <small class="text-body-secondary d-block">Estimasi Harga Jual:</small>
                  <span class="fs-5 fw-bold text-success" id="modalItemSellPrice">Rp 0</span>
                </div>
                <div class="mb-3">
                  <small class="text-body-secondary d-block">Margin Keuntungan Gross:</small>
                  <span class="badge bg-success-subtle text-success border border-success-subtle fs-6" id="modalItemMargin">0%</span>
                </div>
              </div>

              <div class="d-grid gap-2 pt-3 border-top">
                <button class="btn btn-outline-primary btn-sm" onclick="triggerReorderFromModal()">
                  <i class="bi bi-cart-plus me-1"></i> Buat PO Reorder
                </button>
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                  Tutup
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Form Tambah / Edit Barang (Simulasi) -->
<div class="modal fade" id="formItemModal" tabindex="-1" aria-labelledby="formItemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow border-0">
      <div class="modal-header bg-body-tertiary">
        <h5 class="modal-title fw-bold" id="formItemModalLabel">
          <i class="bi bi-plus-circle-fill text-primary me-2"></i>Tambah Barang Baru ke Master
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="itemForm" onsubmit="handleFormSubmit(event)">
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Kode SKU <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-sm" id="formSku" placeholder="misal: SKU-RAW-009" required />
            </div>
            <div class="col-md-8">
              <label class="form-label small fw-semibold">Nama Barang Lengkap <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-sm" id="formName" placeholder="Nama dan spesifikasi produk" required />
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
              <select class="form-select form-select-sm" id="formCategory" required>
                @foreach($categories as $cat)
                  <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Satuan (UOM) <span class="text-danger">*</span></label>
              <select class="form-select form-select-sm" id="formUom" required>
                <option value="Pcs">Pcs</option>
                <option value="Box">Box</option>
                <option value="Roll">Roll</option>
                <option value="Lembar">Lembar</option>
                <option value="Batang">Batang</option>
                <option value="Kg">Kg</option>
                <option value="Unit">Unit</option>
                <option value="Set">Set</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Gudang Simpan</label>
              <select class="form-select form-select-sm" id="formWarehouse">
                @foreach($warehouses as $wh)
                  <option value="{{ $wh['id'] }}">{{ $wh['name'] }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label small fw-semibold">Lokasi Rak / Bin</label>
              <input type="text" class="form-control form-control-sm" id="formBin" placeholder="misal: Rak A1-03" />
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Batas Minimum (Min Stock)</label>
              <input type="number" class="form-control form-control-sm" id="formMinStock" value="20" min="1" required />
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Batas Maksimum (Max Stock)</label>
              <input type="number" class="form-control form-control-sm" id="formMaxStock" value="200" min="1" required />
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Harga Beli Terakhir (Rp)</label>
              <input type="number" class="form-control form-control-sm" id="formBuyPrice" placeholder="Rp 0" required />
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Estimasi Harga Jual (Rp)</label>
              <input type="number" class="form-control form-control-sm" id="formSellPrice" placeholder="Rp 0" required />
            </div>
          </div>
        </div>
        <div class="modal-footer bg-body-tertiary">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm px-4">
            <i class="bi bi-save me-1"></i> Simpan ke Katalog Master
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let currentDetailItem = null;

  // Filter Functions
  function filterByStatus(status) {
    document.getElementById('statusFilter').value = status;
    applyFilters();
  }

  function applyFilters() {
    const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
    const catVal = document.getElementById('categoryFilter').value;
    const statusVal = document.getElementById('statusFilter').value;

    const rows = document.querySelectorAll('.item-row');
    let visible = 0;

    rows.forEach(row => {
      const sku = row.getAttribute('data-sku');
      const name = row.getAttribute('data-name');
      const cat = row.getAttribute('data-category');
      const status = row.getAttribute('data-status');

      const matchesSearch = !searchVal || sku.includes(searchVal) || name.includes(searchVal);
      const matchesCat = !catVal || cat === catVal;
      const matchesStatus = !statusVal || status === statusVal;

      if (matchesSearch && matchesCat && matchesStatus) {
        row.style.display = '';
        visible++;
      } else {
        row.style.display = 'none';
      }
    });

    document.getElementById('visibleCount').textContent = visible;
    const noMatchEl = document.getElementById('noMatchAlert');
    if (visible === 0) {
      noMatchEl.classList.remove('d-none');
    } else {
      noMatchEl.classList.add('d-none');
    }
  }

  // Modal Detail
  function showItemDetail(item) {
    currentDetailItem = item;
    document.getElementById('modalItemName').textContent = item.name;
    document.getElementById('modalItemSku').textContent = item.sku;
    document.getElementById('modalItemCategory').textContent = item.category_name;
    document.getElementById('modalItemUom').textContent = item.uom;
    document.getElementById('modalItemWarehouse').textContent = item.warehouse_name;
    document.getElementById('modalItemBin').textContent = item.bin_location;
    document.getElementById('modalItemMinStock').textContent = item.min_stock + ' ' + item.uom;
    document.getElementById('modalItemMaxStock').textContent = item.max_stock + ' ' + item.uom;

    const buyFormatted = 'Rp ' + Number(item.buy_price).toLocaleString('id-ID');
    const sellFormatted = 'Rp ' + Number(item.sell_price).toLocaleString('id-ID');
    const valuation = 'Rp ' + Number(item.current_stock * item.buy_price).toLocaleString('id-ID');
    
    document.getElementById('modalItemBuyPrice').textContent = buyFormatted;
    document.getElementById('modalItemSellPrice').textContent = sellFormatted;
    document.getElementById('modalItemValuation').textContent = valuation;

    const marginPct = Math.round(((item.sell_price - item.buy_price) / item.buy_price) * 100);
    document.getElementById('modalItemMargin').textContent = '+' + marginPct + '% Margin';

    const statusBadge = document.getElementById('modalItemStatus');
    statusBadge.className = 'badge';
    if (item.status === 'Aman') {
      statusBadge.classList.add('bg-success');
      statusBadge.textContent = 'Stok Aman (' + item.current_stock + ' ' + item.uom + ')';
    } else if (item.status === 'Menipis') {
      statusBadge.classList.add('bg-warning', 'text-dark');
      statusBadge.textContent = 'Stok Menipis (' + item.current_stock + ' ' + item.uom + ')';
    } else {
      statusBadge.classList.add('bg-danger');
      statusBadge.textContent = 'Stok Kosong / Habis';
    }

    const modal = new bootstrap.Modal(document.getElementById('detailItemModal'));
    modal.show();
  }

  function triggerReorderFromModal() {
    if (currentDetailItem) {
      bootstrap.Modal.getInstance(document.getElementById('detailItemModal')).hide();
      window.showToast('Pengajuan pembelian untuk ' + currentDetailItem.sku + ' (' + currentDetailItem.name + ') berhasil diajukan.', 'success', 'Pengadaan PO');
    }
  }

  // Modal Form
  function openAddModal() {
    document.getElementById('formItemModalLabel').innerHTML = '<i class="bi bi-plus-circle-fill text-primary me-2"></i>Tambah Barang Baru ke Master';
    document.getElementById('itemForm').reset();
  }

  function showEditModal(item) {
    document.getElementById('formItemModalLabel').innerHTML = '<i class="bi bi-pencil-square text-primary me-2"></i>Ubah Data Barang (' + item.sku + ')';
    document.getElementById('formSku').value = item.sku;
    document.getElementById('formName').value = item.name;
    document.getElementById('formCategory').value = item.category_id;
    document.getElementById('formUom').value = item.uom;
    document.getElementById('formWarehouse').value = item.warehouse_id;
    document.getElementById('formBin').value = item.bin_location;
    document.getElementById('formMinStock').value = item.min_stock;
    document.getElementById('formMaxStock').value = item.max_stock;
    document.getElementById('formBuyPrice').value = item.buy_price;
    document.getElementById('formSellPrice').value = item.sell_price;

    const modal = new bootstrap.Modal(document.getElementById('formItemModal'));
    modal.show();
  }

  function handleFormSubmit(e) {
    e.preventDefault();
    const sku = document.getElementById('formSku').value;
    const name = document.getElementById('formName').value;

    bootstrap.Modal.getInstance(document.getElementById('formItemModal')).hide();
    window.showToast('Data master barang [' + sku + ' - ' + name + '] berhasil disimpan.', 'success', 'Simpan Master Data');
  }

  function exportDataSimulasi() {
    window.showToast('Mengekspor seluruh katalog (32 SKU) ke format Excel (.xlsx)...', 'info', 'Ekspor Data');
  }
</script>
@endpush
