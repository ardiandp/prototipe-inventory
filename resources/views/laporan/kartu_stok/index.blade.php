@extends('layouts.app')

@section('title', 'Kartu Stok Digital - ' . $card['item']['sku'])

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
      <h3 class="mb-1 fw-bold text-dark">
        <i class="bi bi-journal-text text-primary me-2"></i>Kartu Stok Digital (Stock Card Ledger)
      </h3>
      <p class="text-secondary mb-0 small">
        Buku besar kronologis keluar masuk barang per SKU, mutasi antar gudang, penyesuaian fisik, dan saldo berjalan.
      </p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" onclick="exportStockCard()">
        <i class="bi bi-download me-1"></i>Ekspor Buku Besar
      </button>
      <button class="btn btn-primary btn-sm" onclick="window.print()">
        <i class="bi bi-printer me-1"></i>Cetak Kartu Stok
      </button>
    </div>
  </div>

  <!-- SKU Selector & Item Overview Card -->
  <div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
      <div class="row g-3 align-items-center">
        <!-- SKU Selector Dropdown -->
        <div class="col-12 col-md-5">
          <label class="form-label small fw-bold text-dark mb-1">
            <i class="bi bi-box-seam me-1 text-primary"></i>Pilih SKU / Barang Inventaris:
          </label>
          <select class="form-select form-select-sm fw-semibold border-primary-subtle" onchange="window.location.href='{{ url('/laporan/kartu-stok') }}?sku=' + this.value">
            @foreach($items as $itm)
              <option value="{{ $itm['sku'] }}" {{ $itm['sku'] === $card['item']['sku'] ? 'selected' : '' }}>
                {{ $itm['sku'] }} - {{ $itm['name'] }} (Stok: {{ $itm['current_stock'] }} {{ $itm['uom'] }})
              </option>
            @endforeach
          </select>
        </div>

        <!-- Quick Item Metadata -->
        <div class="col-12 col-md-7">
          <div class="row g-2 text-center text-md-start small">
            <div class="col-6 col-sm-3 border-start-md ps-md-3">
              <span class="text-secondary d-block">Kategori</span>
              <strong class="text-dark">{{ $card['item']['category_name'] }}</strong>
            </div>
            <div class="col-6 col-sm-3 border-start-md ps-md-3">
              <span class="text-secondary d-block">Lokasi Rak</span>
              <strong class="text-dark">{{ $card['item']['warehouse_name'] }}</strong>
              <div class="badge text-bg-light border text-secondary small">{{ $card['item']['bin_location'] }}</div>
            </div>
            <div class="col-6 col-sm-3 border-start-md ps-md-3">
              <span class="text-secondary d-block">Harga Beli Satuan</span>
              <strong class="text-dark">Rp {{ number_format($card['item']['buy_price'], 0, ',', '.') }}</strong>
            </div>
            <div class="col-6 col-sm-3 border-start-md ps-md-3">
              <span class="text-secondary d-block">Status Batas</span>
              <span class="badge text-bg-{{ $card['item']['status'] === 'Aman' ? 'success' : ($card['item']['status'] === 'Menipis' ? 'warning' : 'danger') }}">
                {{ $card['item']['status'] }} (Min: {{ $card['item']['min_stock'] }})
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Metric Balance Cards -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0 bg-secondary-subtle text-secondary p-2 rounded-3 me-3">
              <i class="bi bi-calendar-event fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Saldo Awal Bulan</div>
              <h5 class="fw-bold mb-0 text-dark">{{ $card['opening_balance'] }} {{ $card['item']['uom'] }}</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0 bg-success-subtle text-success p-2 rounded-3 me-3">
              <i class="bi bi-box-arrow-in-down fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Total Masuk (Inbound)</div>
              <h5 class="fw-bold mb-0 text-success">+{{ $card['total_in'] }} {{ $card['item']['uom'] }}</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0 bg-warning-subtle text-warning-emphasis p-2 rounded-3 me-3">
              <i class="bi bi-box-arrow-up-right fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Total Keluar (Outbound)</div>
              <h5 class="fw-bold mb-0 text-warning-emphasis">-{{ $card['total_out'] }} {{ $card['item']['uom'] }}</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0 bg-primary-subtle text-primary p-2 rounded-3 me-3">
              <i class="bi bi-cash-stack fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Saldo Akhir Fisik</div>
              <h5 class="fw-bold mb-0 text-primary">{{ $card['closing_balance'] }} {{ $card['item']['uom'] }}</h5>
              <span class="small text-secondary">{{ $card['closing_valuation_formatted'] }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stock Ledger Table Card -->
  <div class="card border-0 shadow-sm rounded-3 mb-4" id="printableCard">
    <div class="card-header bg-white py-3 border-bottom-0">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h5 class="fw-bold text-dark mb-0">
            Riwayat Mutasi Saldo: <span class="text-primary">{{ $card['item']['name'] }}</span>
          </h5>
          <span class="text-secondary small">Kode SKU: <strong>{{ $card['item']['sku'] }}</strong> | Satuan: {{ $card['item']['uom'] }}</span>
        </div>
        <div class="d-flex gap-2">
          <input type="text" id="ledgerSearch" class="form-control form-control-sm" placeholder="Saring No. Ref atau pihak..." style="max-width: 250px;">
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="ledgerTable">
        <thead class="table-light text-secondary small text-uppercase">
          <tr>
            <th class="ps-3" style="width: 140px;">Waktu & Tanggal</th>
            <th style="width: 140px;">No. Dokumen</th>
            <th>Jenis Mutasi</th>
            <th>Pihak / Rekanan / Tujuan</th>
            <th class="text-center text-success" style="width: 110px;">Masuk (+)</th>
            <th class="text-center text-danger" style="width: 110px;">Keluar (-)</th>
            <th class="text-center text-primary" style="width: 120px;">Saldo Berjalan</th>
            <th>Operator</th>
            <th class="pe-3">Keterangan</th>
          </tr>
        </thead>
        <tbody class="small">
          @forelse($card['entries'] as $idx => $entry)
            <tr class="ledger-row">
              <td class="ps-3 text-secondary">
                <i class="bi bi-clock me-1"></i>{{ $entry['date'] }}
              </td>
              <td>
                <span class="fw-bold text-dark font-monospace">{{ $entry['ref_no'] }}</span>
              </td>
              <td>
                <span class="badge text-bg-{{ $entry['badge'] }} rounded-pill px-2">
                  {{ $entry['type_label'] }}
                </span>
              </td>
              <td>
                <span class="fw-medium text-dark">{{ $entry['party'] }}</span>
              </td>
              <td class="text-center fw-bold text-success">
                {{ $entry['in_qty'] > 0 ? '+' . $entry['in_qty'] : '-' }}
              </td>
              <td class="text-center fw-bold text-danger">
                {{ $entry['out_qty'] > 0 ? '-' . $entry['out_qty'] : '-' }}
              </td>
              <td class="text-center">
                <span class="badge text-bg-primary-subtle text-primary border fs-6 px-3 py-1">
                  {{ $entry['balance'] }} {{ $card['item']['uom'] }}
                </span>
              </td>
              <td>
                <span class="text-dark">{{ $entry['operator'] }}</span>
              </td>
              <td class="pe-3 text-secondary small">
                {{ $entry['notes'] }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center py-4 text-secondary">Belum ada mutasi tercatat untuk SKU ini.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer bg-white border-top-0 py-3">
      <div class="d-flex justify-content-between align-items-center">
        <span class="text-secondary small">Menampilkan {{ count($card['entries']) }} baris mutasi persediaan</span>
        <span class="badge text-bg-light border text-secondary">Metode Pencatatan Perpetual Rata-rata</span>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.getElementById('ledgerSearch')?.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.ledger-row');

    rows.forEach(r => {
      const text = r.innerText.toLowerCase();
      r.style.display = (!q || text.includes(q)) ? '' : 'none';
    });
  });

  function exportStockCard() {
    window.showToast('Mengekspor buku besar kartu stok {{ $card["item"]["sku"] }} ke file Excel (CSV)...', 'info', 'Ekspor Kartu Stok');
  }
</script>
@endpush
