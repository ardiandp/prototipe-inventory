@extends('layouts.app')

@section('title', 'Dashboard Inventaris')
@section('page_title', 'Dashboard Ringkasan Inventaris')
@section('page_description', 'Pemantauan persediaan stok barang, pergerakan gudang, dan aktivitas transaksi harian')

@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<!-- Interactive Role Context Banner -->
<div class="card border-{{ $currentRole['badge'] }}-subtle shadow-sm mb-4 bg-body">
  <div class="card-body p-3 p-md-4">
    <div class="row align-items-center g-3">
      <div class="col-md-8 d-flex align-items-center gap-3">
        <img src="{{ asset('theme/AdminLTE-4/dist/assets/img/' . $currentRole['avatar']) }}" alt="Avatar" class="rounded-circle shadow border border-2 border-{{ $currentRole['badge'] }}" width="56" height="56" />
        <div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
            <h5 class="fw-bold mb-0">{{ $currentRole['user_name'] }}</h5>
            <span class="badge bg-{{ $currentRole['badge'] }} text-white">
              <i class="bi {{ $currentRole['icon'] }} me-1"></i> {{ $currentRole['name'] }}
            </span>
            <span class="badge bg-success-subtle text-success border border-success-subtle d-none d-sm-inline-block">
              <i class="bi bi-shield-check me-1"></i> Mode Interaktif Demo
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
            <i class="bi bi-arrow-repeat me-1"></i> Ganti Peran
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li class="dropdown-header fw-bold">Pilih Role Pengguna:</li>
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

<!-- 4 KPI Stat Cards -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body">
      <div class="card-body d-flex align-items-center">
        <div class="d-inline-flex p-3 rounded-3 bg-primary-subtle text-primary me-3">
          <i class="bi bi-boxes fs-3"></i>
        </div>
        <div>
          <div class="text-body-secondary small fw-medium">Total SKU Barang</div>
          <h3 class="fw-bold mb-0">{{ $stats['total_sku'] }} <span class="fs-6 text-muted fw-normal">Item</span></h3>
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
        <div class="overflow-hidden">
          <div class="text-body-secondary small fw-medium">Total Valuasi Aset</div>
          <h3 class="fw-bold mb-0 fs-5 text-truncate" title="{{ $stats['total_valuation_formatted'] }}">{{ $stats['total_valuation_formatted'] }}</h3>
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
          <div class="text-body-secondary small fw-medium">Peringatan Stok Kritis</div>
          <h3 class="fw-bold mb-0 text-warning">{{ $stats['alert_count'] }} <span class="fs-6 text-muted fw-normal">SKU</span></h3>
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
          <h3 class="fw-bold mb-0">{{ number_format($stats['monthly_movements']) }} <span class="fs-6 text-muted fw-normal">Qty</span></h3>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Section -->
<div class="row g-3 mb-4">
  <!-- Chart 1: Tren Mutasi Mingguan -->
  <div class="col-lg-8">
    <div class="card border shadow-sm h-100">
      <div class="card-header bg-body py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h6 class="card-title fw-bold mb-0">
          <i class="bi bi-graph-up text-primary me-2"></i>Tren Pergerakan Stok (7 Hari Terakhir)
        </h6>
        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
            <i class="bi bi-arrow-down-left me-1"></i> Inbound (Masuk)
          </span>
          <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
            <i class="bi bi-arrow-up-right me-1"></i> Outbound (Keluar)
          </span>
        </div>
      </div>
      <div class="card-body">
        <div style="position: relative; height: 260px;">
          <canvas id="movementChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart 2: Komposisi Persediaan per Kategori -->
  <div class="col-lg-4">
    <div class="card border shadow-sm h-100">
      <div class="card-header bg-body py-3">
        <h6 class="card-title fw-bold mb-0">
          <i class="bi bi-pie-chart text-info me-2"></i>Distribusi Kategori Barang
        </h6>
      </div>
      <div class="card-body d-flex flex-column justify-content-center">
        <div style="position: relative; height: 200px;" class="mb-3">
          <canvas id="categoryDonutChart"></canvas>
        </div>
        <div class="row g-2 text-center small text-secondary">
          <div class="col-4"><span class="badge bg-primary">Logam</span> 6 SKU</div>
          <div class="col-4"><span class="badge bg-info">Elektronik</span> 7 SKU</div>
          <div class="col-4"><span class="badge bg-warning text-dark">Packaging</span> 6 SKU</div>
          <div class="col-6"><span class="badge bg-success">Safety Gear</span> 6 SKU</div>
          <div class="col-6"><span class="badge bg-danger">Finish Goods</span> 7 SKU</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Operational Section: Quick Alerts & Warehouse Capacity -->
<div class="row g-3 mb-4">
  <!-- Quick Alerts Table -->
  <div class="col-lg-8">
    <div class="card border shadow-sm h-100">
      <div class="card-header bg-body py-3 d-flex align-items-center justify-content-between">
        <div>
          <h6 class="card-title fw-bold mb-0">
            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Peringatan Stok Kritis (Perlu Tindakan)
          </h6>
          <small class="text-body-secondary">Item barang yang berada di bawah batas minimum pengamanan</small>
        </div>
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
          {{ count($lowStockItems) }} Item Urgent
        </span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
              <tr>
                <th>SKU & Barang</th>
                <th>Lokasi Rak</th>
                <th class="text-center">Stok / Min</th>
                <th class="text-center">Status</th>
                <th class="text-end">Aksi Cepat</th>
              </tr>
            </thead>
            <tbody class="small">
              @forelse($lowStockItems as $item)
                <tr>
                  <td>
                    <div class="fw-bold">{{ $item['name'] }}</div>
                    <code class="text-primary">{{ $item['sku'] }}</code> &bull; <span class="text-secondary">{{ $item['category_name'] }}</span>
                  </td>
                  <td>
                    <i class="bi bi-geo-alt text-muted me-1"></i>{{ $item['bin_location'] }}<br>
                    <small class="text-secondary">{{ $item['warehouse_name'] }}</small>
                  </td>
                  <td class="text-center">
                    <div class="fw-bold {{ $item['current_stock'] == 0 ? 'text-danger' : 'text-warning' }}">
                      {{ $item['current_stock'] }} {{ $item['uom'] }}
                    </div>
                    <small class="text-muted">Min: {{ $item['min_stock'] }}</small>
                  </td>
                  <td class="text-center">
                    @if($item['current_stock'] == 0)
                      <span class="badge bg-danger">Habis</span>
                    @else
                      <span class="badge bg-warning text-dark">Menipis</span>
                    @endif
                  </td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary" onclick="window.showToast('Pengajuan pembelian untuk {{ $item['sku'] }} ({{ $item['name'] }}) berhasil diajukan ke Purchasing.', 'success', 'Reorder PO')">
                      <i class="bi bi-cart-plus me-1"></i> Reorder
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4 text-muted">
                    <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
                    Semua stok barang berada di atas batas aman.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Warehouse Utilization Overview -->
  <div class="col-lg-4">
    <div class="card border shadow-sm h-100">
      <div class="card-header bg-body py-3">
        <h6 class="card-title fw-bold mb-0">
          <i class="bi bi-buildings text-primary me-2"></i>Utilisasi Kapasitas Gudang
        </h6>
      </div>
      <div class="card-body">
        @foreach($warehouses as $wh)
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <div>
                <span class="fw-semibold small d-block">{{ $wh['name'] }}</span>
                <span class="text-secondary" style="font-size: 0.75rem;">{{ $wh['city'] }} &bull; {{ $wh['total_bins'] }} Rak Bin</span>
              </div>
              <span class="badge {{ $wh['capacity_pct'] > 75 ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' }} fw-bold">
                {{ $wh['capacity_pct'] }}%
              </span>
            </div>
            <div class="progress" style="height: 8px;">
              <div class="progress-bar {{ $wh['capacity_pct'] > 75 ? 'bg-danger' : 'bg-primary' }}" role="progressbar" style="width: {{ $wh['capacity_pct'] }}%;" aria-valuenow="{{ $wh['capacity_pct'] }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        @endforeach

        <div class="p-3 bg-body-tertiary rounded border small text-secondary">
          <i class="bi bi-info-circle text-primary me-1"></i>
          Penyimpanan di Cikarang Central mendekati batas optimal. Dianjurkan memindahkan stok ke transit Karawang Timur.
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Transactions Log -->
<div class="card border shadow-sm mb-4">
  <div class="card-header bg-body py-3 d-flex align-items-center justify-content-between">
    <div>
      <h6 class="card-title fw-bold mb-0">
        <i class="bi bi-clock-history text-primary me-2"></i>Aktivitas Mutasi & Transaksi Terkini
      </h6>
      <small class="text-body-secondary">Catatan pergerakan stok, penerimaan surat jalan, dan pengeluaran barang</small>
    </div>
    <button class="btn btn-outline-secondary btn-sm" onclick="window.showToast('Memuat log mutasi lengkap...', 'info', 'Refresh Data')">
      <i class="bi bi-arrow-clockwise me-1"></i> Refresh
    </button>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light small">
          <tr>
            <th>Waktu & No. Ref</th>
            <th>Jenis Mutasi</th>
            <th>Item Barang & SKU</th>
            <th class="text-center">Kuantitas</th>
            <th>Alur / Pihak Terkait</th>
            <th>Petugas</th>
            <th class="text-center">Status</th>
          </tr>
        </thead>
        <tbody class="small">
          @foreach($recentTransactions as $trx)
            <tr>
              <td>
                <div class="fw-semibold">{{ $trx['ref_no'] }}</div>
                <small class="text-secondary">{{ $trx['date'] }}</small>
              </td>
              <td>
                @if($trx['type'] === 'inbound')
                  <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-box-arrow-in-down me-1"></i> Inbound</span>
                @elseif($trx['type'] === 'outbound')
                  <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-box-arrow-up-right me-1"></i> Outbound</span>
                @elseif($trx['type'] === 'transfer')
                  <span class="badge bg-info-subtle text-info border border-info-subtle"><i class="bi bi-arrow-left-right me-1"></i> Transfer</span>
                @else
                  <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="bi bi-clipboard-check me-1"></i> Opname</span>
                @endif
              </td>
              <td>
                <div class="fw-bold">{{ $trx['item_name'] }}</div>
                <code class="text-muted">{{ $trx['sku'] }}</code>
              </td>
              <td class="text-center fw-bold">
                <span class="{{ $trx['qty'] > 0 ? 'text-success' : 'text-danger' }}">
                  {{ $trx['qty'] > 0 ? '+' : '' }}{{ $trx['qty'] }}
                </span>
                <span class="text-secondary small fw-normal">{{ $trx['uom'] }}</span>
              </td>
              <td>
                <div class="text-body">{{ $trx['party'] }}</div>
                <small class="text-secondary"><i class="bi bi-geo-alt me-1"></i>{{ $trx['location'] }}</small>
              </td>
              <td>
                <i class="bi bi-person me-1 text-secondary"></i>{{ $trx['operator'] }}
              </td>
              <td class="text-center">
                <span class="badge bg-success"><i class="bi bi-check me-1"></i>{{ $trx['status'] }}</span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js 4.4.x -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // 1. Inbound vs Outbound Movement Chart
    const movementCtx = document.getElementById('movementChart');
    if (movementCtx) {
      new Chart(movementCtx, {
        type: 'bar',
        data: {
          labels: {!! json_encode($chartData['labels']) !!},
          datasets: [
            {
              label: 'Inbound (Masuk)',
              data: {!! json_encode($chartData['inbound']) !!},
              backgroundColor: 'rgba(13, 110, 253, 0.75)',
              borderColor: '#0d6efd',
              borderWidth: 1,
              borderRadius: 4
            },
            {
              label: 'Outbound (Keluar)',
              data: {!! json_encode($chartData['outbound']) !!},
              backgroundColor: 'rgba(255, 193, 7, 0.75)',
              borderColor: '#ffc107',
              borderWidth: 1,
              borderRadius: 4
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(0, 0, 0, 0.05)'
              }
            },
            x: {
              grid: {
                display: false
              }
            }
          }
        }
      });
    }

    // 2. Category Donut Chart
    const categoryCtx = document.getElementById('categoryDonutChart');
    if (categoryCtx) {
      new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
          labels: {!! json_encode($chartData['categories']['labels']) !!},
          datasets: [{
            data: {!! json_encode($chartData['categories']['counts']) !!},
            backgroundColor: {!! json_encode($chartData['categories']['colors']) !!},
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          cutout: '65%'
        }
      });
    }
  });
</script>
@endpush
