@extends('layouts.app')

@section('title', 'Laporan Mutasi & Valuasi Persediaan')

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
      <h3 class="mb-1 fw-bold text-dark">
        <i class="bi bi-graph-up-arrow text-success me-2"></i>Laporan Mutasi & Valuasi Persediaan
      </h3>
      <p class="text-secondary mb-0 small">
        Rekapitulasi pergerakan kuantitas saldo awal, mutasi masuk, mutasi keluar, dan nilai valuasi aset stok gudang.
      </p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" onclick="exportReportExcel()">
        <i class="bi bi-file-earmark-spreadsheet me-1"></i>Ekspor Excel (CSV)
      </button>
      <button class="btn btn-primary btn-sm" onclick="window.print()">
        <i class="bi bi-printer me-1"></i>Cetak Laporan
      </button>
    </div>
  </div>

  <!-- Metric Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0 bg-primary-subtle text-primary p-2 rounded-3 me-3">
              <i class="bi bi-wallet2 fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Total Valuasi Persediaan</div>
              <h5 class="fw-bold mb-0 text-primary" id="kpiTotalValuation">{{ $report['summary']['total_valuation_formatted'] }}</h5>
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
              <h5 class="fw-bold mb-0 text-dark">{{ number_format($report['summary']['total_inbound'], 0, ',', '.') }} Unit</h5>
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
              <h5 class="fw-bold mb-0 text-dark">{{ number_format($report['summary']['total_outbound'], 0, ',', '.') }} Unit</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0 bg-info-subtle text-info p-2 rounded-3 me-3">
              <i class="bi bi-boxes fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Total Saldo Akhir</div>
              <h5 class="fw-bold mb-0 text-dark">{{ number_format($report['summary']['total_ending_stock'], 0, ',', '.') }} Unit ({{ $report['summary']['total_sku'] }} SKU)</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter & Report Table Card -->
  <div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
      <div class="row g-2 align-items-center">
        <!-- Live Search -->
        <div class="col-12 col-md-3">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-secondary"></i></span>
            <input type="text" id="reportSearch" class="form-control bg-light border-start-0" placeholder="Cari SKU atau nama barang...">
          </div>
        </div>

        <!-- Filter Kategori -->
        <div class="col-6 col-md-3">
          <select id="filterCategory" class="form-select form-select-sm">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
              <option value="{{ $cat['name'] }}">{{ $cat['name'] }}</option>
            @endforeach
          </select>
        </div>

        <!-- Filter Gudang -->
        <div class="col-6 col-md-3">
          <select id="filterWarehouse" class="form-select form-select-sm">
            <option value="">Semua Gudang</option>
            @foreach($warehouses as $wh)
              <option value="{{ $wh['name'] }}">{{ $wh['name'] }}</option>
            @endforeach
          </select>
        </div>

        <!-- Filter Status Stok -->
        <div class="col-6 col-md-2">
          <select id="filterStatus" class="form-select form-select-sm">
            <option value="">Semua Status</option>
            <option value="Aman">Aman</option>
            <option value="Menipis">Menipis</option>
            <option value="Habis">Habis</option>
          </select>
        </div>

        <!-- Reset Button -->
        <div class="col-6 col-md-1 text-end">
          <button class="btn btn-sm btn-light border w-100" onclick="resetFilters()">
            <i class="bi bi-arrow-counterclockwise"></i>
          </button>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="reportTable">
        <thead class="table-light text-secondary small text-uppercase">
          <tr>
            <th class="ps-3" style="width: 120px;">SKU</th>
            <th>Nama Barang & Kategori</th>
            <th>Lokasi Rak</th>
            <th class="text-center" style="width: 80px;">Saldo Awal</th>
            <th class="text-center text-success" style="width: 80px;">Masuk (+)</th>
            <th class="text-center text-danger" style="width: 80px;">Keluar (-)</th>
            <th class="text-center text-secondary" style="width: 70px;">Adj</th>
            <th class="text-center text-primary" style="width: 90px;">Saldo Akhir</th>
            <th class="text-end" style="width: 120px;">Harga Satuan</th>
            <th class="text-end" style="width: 140px;">Valuasi Persediaan</th>
            <th class="text-center pe-3" style="width: 90px;">Status</th>
          </tr>
        </thead>
        <tbody class="small">
          @forelse($report['rows'] as $row)
            <tr class="report-row"
                data-sku="{{ $row['sku'] }}"
                data-name="{{ $row['name'] }}"
                data-category="{{ $row['category'] }}"
                data-warehouse="{{ $row['warehouse'] }}"
                data-status="{{ $row['status'] }}"
                data-valuation="{{ $row['valuation'] }}">
              <td class="ps-3 font-monospace fw-bold text-dark">
                <a href="{{ url('/laporan/kartu-stok') }}?sku={{ $row['sku'] }}" class="text-decoration-none text-primary" title="Buka Kartu Stok">
                  {{ $row['sku'] }} <i class="bi bi-box-arrow-up-right small"></i>
                </a>
              </td>
              <td>
                <span class="fw-semibold text-dark d-block">{{ $row['name'] }}</span>
                <span class="badge text-bg-light border text-secondary small">{{ $row['category'] }}</span>
              </td>
              <td>
                <span class="text-dark d-block">{{ $row['warehouse'] }}</span>
                <span class="badge text-bg-secondary-subtle text-secondary border small">{{ $row['bin'] }}</span>
              </td>
              <td class="text-center text-secondary">{{ $row['beginning_stock'] }} {{ $row['uom'] }}</td>
              <td class="text-center fw-bold text-success">+{{ $row['inbound_qty'] }}</td>
              <td class="text-center fw-bold text-danger">-{{ $row['outbound_qty'] }}</td>
              <td class="text-center">
                @if($row['adjustment_qty'] > 0)
                  <span class="text-info fw-bold">+{{ $row['adjustment_qty'] }}</span>
                @elseif($row['adjustment_qty'] < 0)
                  <span class="text-danger fw-bold">{{ $row['adjustment_qty'] }}</span>
                @else
                  <span class="text-muted">0</span>
                @endif
              </td>
              <td class="text-center">
                <span class="badge text-bg-primary-subtle text-primary border fs-6 px-2 py-1">
                  {{ $row['ending_stock'] }} {{ $row['uom'] }}
                </span>
              </td>
              <td class="text-end text-secondary">
                Rp {{ number_format($row['buy_price'], 0, ',', '.') }}
              </td>
              <td class="text-end fw-bold text-dark">
                {{ $row['valuation_formatted'] }}
              </td>
              <td class="text-center pe-3">
                <span class="badge text-bg-{{ $row['status_badge'] }} rounded-pill px-2 py-1">
                  {{ $row['status'] }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="11" class="text-center py-4 text-secondary">Tidak ada data persediaan.</td>
            </tr>
          @endforelse
        </tbody>
        <tfoot class="table-light fw-bold">
          <tr>
            <td colspan="3" class="ps-3 text-dark">TOTAL REKAPITULASI KESELURUHAN</td>
            <td class="text-center">{{ number_format($report['summary']['total_beginning_stock'], 0, ',', '.') }}</td>
            <td class="text-center text-success">+{{ number_format($report['summary']['total_inbound'], 0, ',', '.') }}</td>
            <td class="text-center text-danger">-{{ number_format($report['summary']['total_outbound'], 0, ',', '.') }}</td>
            <td class="text-center text-muted">0</td>
            <td class="text-center text-primary fs-6">{{ number_format($report['summary']['total_ending_stock'], 0, ',', '.') }}</td>
            <td class="text-end text-muted">-</td>
            <td class="text-end text-primary fs-6" id="footerTotalValuation">{{ $report['summary']['total_valuation_formatted'] }}</td>
            <td class="pe-3"></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="card-footer bg-white border-top-0 py-3">
      <div class="d-flex justify-content-between align-items-center">
        <span class="text-secondary small" id="rowCountInfo">Menampilkan {{ count($report['rows']) }} SKU persediaan barang</span>
        <span class="badge text-bg-light border text-secondary">Periode Berjalan: November 2026</span>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.getElementById('reportSearch')?.addEventListener('input', applyFilters);
  document.getElementById('filterCategory')?.addEventListener('change', applyFilters);
  document.getElementById('filterWarehouse')?.addEventListener('change', applyFilters);
  document.getElementById('filterStatus')?.addEventListener('change', applyFilters);

  function applyFilters() {
    const q = document.getElementById('reportSearch').value.toLowerCase().trim();
    const cat = document.getElementById('filterCategory').value;
    const wh = document.getElementById('filterWarehouse').value;
    const st = document.getElementById('filterStatus').value;

    const rows = document.querySelectorAll('.report-row');
    let visibleCount = 0;
    let visibleValuation = 0;

    rows.forEach(r => {
      const sku = r.getAttribute('data-sku').toLowerCase();
      const name = r.getAttribute('data-name').toLowerCase();
      const category = r.getAttribute('data-category');
      const warehouse = r.getAttribute('data-warehouse');
      const status = r.getAttribute('data-status');
      const val = parseInt(r.getAttribute('data-valuation')) || 0;

      const matchesQ = !q || sku.includes(q) || name.includes(q);
      const matchesCat = !cat || category === cat;
      const matchesWh = !wh || warehouse === wh;
      const matchesSt = !st || status === st;

      if (matchesQ && matchesCat && matchesWh && matchesSt) {
        r.style.display = '';
        visibleCount++;
        visibleValuation += val;
      } else {
        r.style.display = 'none';
      }
    });

    document.getElementById('rowCountInfo').innerText = `Menampilkan ${visibleCount} SKU persediaan barang`;
    document.getElementById('footerTotalValuation').innerText = `Rp ${visibleValuation.toLocaleString('id-ID')}`;
  }

  function resetFilters() {
    document.getElementById('reportSearch').value = '';
    document.getElementById('filterCategory').value = '';
    document.getElementById('filterWarehouse').value = '';
    document.getElementById('filterStatus').value = '';
    applyFilters();
    window.showToast('Filter laporan mutasi persediaan telah direset', 'info', 'Filter');
  }

  function exportReportExcel() {
    window.showToast('Mengekspor rekapitulasi mutasi dan valuasi 32 SKU persediaan ke file Excel...', 'info', 'Ekspor Laporan');
  }
</script>
@endpush
