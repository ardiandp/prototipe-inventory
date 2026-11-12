@extends('layouts.app')

@section('title', 'Stock Opname & Penyesuaian Selisih (Stock Take)')

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
      <h3 class="mb-1 fw-bold text-dark">
        <i class="bi bi-clipboard-check text-success me-2"></i>Stock Opname & Penyesuaian Selisih
      </h3>
      <p class="text-secondary mb-0 small">
        Pencatatan hasil hitung fisik gudang, audit selisih saldo vs sistem, dan alur persetujuan penyesuaian (adjustment write-off).
      </p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" onclick="exportOpnameData()">
        <i class="bi bi-download me-1"></i>Ekspor Hasil Audit
      </button>
      @if(\App\Support\RoleManager::can('opname'))
      <button class="btn btn-outline-success btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#modalInputCount">
        <i class="bi bi-pencil-square me-1"></i>Input Hitung Fisik
      </button>
      <button class="btn btn-success btn-sm text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#modalAddOpname">
        <i class="bi bi-plus-circle me-1"></i>+ Jadwal Sesi Opname
      </button>
      @endif
    </div>
  </div>

  <!-- Metric Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0 bg-success-subtle text-success p-2 rounded-3 me-3">
              <i class="bi bi-calendar2-check fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Total Sesi Audit</div>
              <h5 class="fw-bold mb-0 text-dark">{{ count($sessions) }} Sesi</h5>
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
              <i class="bi bi-check-all fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Selesai & Disetujui</div>
              <h5 class="fw-bold mb-0 text-dark">
                {{ count(array_filter($sessions, fn($s) => $s['status'] === 'APPROVED')) }} Sesi
              </h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0 bg-warning-subtle text-warning p-2 rounded-3 me-3">
              <i class="bi bi-exclamation-triangle fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Menunggu Approval</div>
              <h5 class="fw-bold mb-0 text-dark">
                {{ count(array_filter($sessions, fn($s) => $s['status'] === 'PENDING_APPROVAL')) }} Sesi Selisih
              </h5>
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
              <i class="bi bi-play-circle fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Sedang Berjalan</div>
              <h5 class="fw-bold mb-0 text-dark">
                {{ count(array_filter($sessions, fn($s) => in_array($s['status'], ['IN_PROGRESS', 'DRAFT']))) }} Jadwal
              </h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter & Table Card -->
  <div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
      <div class="row g-2 align-items-center">
        <!-- Live Search -->
        <div class="col-12 col-md-4">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light border-end-0">
              <i class="bi bi-search text-secondary"></i>
            </span>
            <input type="text" id="opnameSearch" class="form-control bg-light border-start-0" placeholder="Cari ID Opname, Judul Sesi, Gudang, atau Auditor...">
          </div>
        </div>

        <!-- Filter Status -->
        <div class="col-6 col-md-3">
          <select id="filterStatus" class="form-select form-select-sm">
            <option value="">Semua Status Audit</option>
            <option value="APPROVED">Selesai & Disetujui (Approved)</option>
            <option value="PENDING_APPROVAL">Menunggu Approval Manager</option>
            <option value="IN_PROGRESS">Sedang Berjalan (Hitung Fisik)</option>
            <option value="DRAFT">Draft / Terjadwal</option>
          </select>
        </div>

        <!-- Filter Gudang -->
        <div class="col-6 col-md-3">
          <select id="filterWarehouse" class="form-select form-select-sm">
            <option value="">Semua Fasilitas Gudang</option>
            @foreach($warehouses as $wh)
              <option value="{{ $wh['name'] }}">{{ $wh['name'] }}</option>
            @endforeach
          </select>
        </div>

        <!-- Reset Button -->
        <div class="col-12 col-md-2 text-md-end">
          <button class="btn btn-sm btn-light border w-100" onclick="resetFilters()">
            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
          </button>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="opnameTable">
        <thead class="table-light text-secondary small text-uppercase">
          <tr>
            <th class="ps-3" style="width: 140px;">No. Sesi</th>
            <th>Judul & Lingkup Audit</th>
            <th>Fasilitas Gudang</th>
            <th>Auditor / Petugas</th>
            <th class="text-center">Hasil Hitung</th>
            <th class="text-end">Nilai Selisih</th>
            <th class="text-center">Status</th>
            <th class="text-end pe-3" style="width: 130px;">Aksi</th>
          </tr>
        </thead>
        <tbody class="small">
          @forelse($sessions as $session)
            <tr class="opname-row"
                data-id="{{ $session['id'] }}"
                data-title="{{ $session['title'] }}"
                data-warehouse="{{ $session['warehouse'] }}"
                data-auditor="{{ $session['auditor'] }}"
                data-status="{{ $session['status'] }}">
              <td class="ps-3">
                <span class="fw-bold text-dark d-block">{{ $session['id'] }}</span>
                <span class="text-secondary small"><i class="bi bi-calendar3 me-1"></i>{{ $session['date'] }}</span>
              </td>
              <td>
                <span class="fw-semibold text-dark d-block">{{ $session['title'] }}</span>
                <span class="text-secondary small fst-italic">{{ Str::limit($session['notes'], 55) }}</span>
              </td>
              <td>
                <span class="text-dark fw-medium d-block">{{ $session['warehouse'] }}</span>
                <span class="badge text-bg-secondary-subtle text-secondary border small">Audit Fasilitas</span>
              </td>
              <td>
                <div class="text-dark fw-medium"><i class="bi bi-person-check me-1 text-primary"></i>{{ $session['auditor'] }}</div>
                <div class="text-secondary small">Approver: <strong>{{ $session['approver'] ?? '-' }}</strong></div>
              </td>
              <td class="text-center">
                @if($session['discrepancy_count'] == 0 && $session['status'] === 'APPROVED')
                  <span class="badge text-bg-success-subtle text-success border">
                    <i class="bi bi-check-circle me-1"></i>100% Cocok ({{ $session['total_items_checked'] }} SKU)
                  </span>
                @elseif($session['discrepancy_count'] > 0)
                  <span class="badge text-bg-warning-subtle text-warning-emphasis border">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $session['discrepancy_count'] }} Selisih / {{ $session['total_items_checked'] }} SKU
                  </span>
                @else
                  <span class="badge text-bg-light border text-secondary">
                    {{ $session['total_items_checked'] }} SKU Diperiksa
                  </span>
                @endif
              </td>
              <td class="text-end">
                @if($session['net_variance_value'] < 0)
                  <span class="fw-bold text-danger">Rp {{ number_format($session['net_variance_value'], 0, ',', '.') }}</span>
                  <div class="small text-danger">Minus (Shortage)</div>
                @elseif($session['net_variance_value'] > 0)
                  <span class="fw-bold text-info">Rp +{{ number_format($session['net_variance_value'], 0, ',', '.') }}</span>
                  <div class="small text-info">Surplus</div>
                @else
                  <span class="text-success fw-bold">Rp 0</span>
                  <div class="small text-secondary">Nol Selisih</div>
                @endif
              </td>
              <td class="text-center">
                <span class="badge text-bg-{{ $session['status_badge'] }} rounded-pill px-2 py-1">
                  {{ $session['status_label'] }}
                </span>
              </td>
              <td class="text-end pe-3">
                <div class="btn-group btn-group-sm">
                  <button type="button" class="btn btn-outline-info" title="Lihat Hasil & Selisih" onclick="showDetailOpname('{{ $session['id'] }}')">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button type="button" class="btn btn-outline-secondary" title="Cetak Berita Acara (BAST)" onclick="showPrintBAST('{{ $session['id'] }}')">
                    <i class="bi bi-printer"></i>
                  </button>
                  @if($session['status'] === 'PENDING_APPROVAL' && in_array($currentRole['id'] ?? 'wh_manager', ['wh_manager', 'finance', 'admin']))
                  <button type="button" class="btn btn-outline-success" title="Approve Penyesuaian Selisih" onclick="quickApproveOpname('{{ $session['id'] }}', this)">
                    <i class="bi bi-check-lg"></i>
                  </button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-4 text-secondary">Belum ada riwayat stock opname.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer bg-white border-top-0 py-3">
      <div class="d-flex justify-content-between align-items-center">
        <span class="text-secondary small" id="rowCountInfo">Menampilkan {{ count($sessions) }} sesi stock opname</span>
        <span class="badge text-bg-light border text-secondary">Alur Verifikasi Fisik & Persetujuan Adjustment</span>
      </div>
    </div>
  </div>
</div>

<!-- ============================================== -->
<!-- MODAL: BUAT JADWAL SESI OPNAME BARU            -->
<!-- ============================================== -->
<div class="modal fade" id="modalAddOpname" tabindex="-1" aria-labelledby="modalAddOpnameLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success-subtle border-0">
        <h5 class="modal-title fw-bold text-dark" id="modalAddOpnameLabel">
          <i class="bi bi-calendar2-plus text-success me-2"></i>Jadwalkan Sesi Stock Opname Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formAddOpname" onsubmit="handleAddOpnameSubmit(event)">
        <div class="modal-body p-4">
          <div class="alert alert-success py-2 small d-flex align-items-center mb-3">
            <i class="bi bi-info-circle-fill fs-5 me-2 text-success"></i>
            <div>
              Pembuatan jadwal audit fisik inventaris untuk menghitung ulang kesesuaian saldo fisik dengan data sistem.
            </div>
          </div>

          <div class="row g-3">
            <div class="col-12">
              <label class="form-label small fw-bold">Judul Sesi Audit <span class="text-danger">*</span></label>
              <input type="text" id="opnameTitle" class="form-control form-control-sm" placeholder="Contoh: Audit Siklus Rak A1 Bahan Logam" required>
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold">Fasilitas Gudang <span class="text-danger">*</span></label>
              <select id="opnameWarehouse" class="form-select form-select-sm" required>
                @foreach($warehouses as $wh)
                  <option value="{{ $wh['name'] }}">{{ $wh['name'] }} ({{ $wh['city'] }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="form-label small fw-bold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
              <input type="date" id="opnameDate" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-bold">Auditor Pelaksana</label>
              <input type="text" id="opnameAuditor" class="form-control form-control-sm" value="{{ $currentRole['user_name'] ?? 'Hendra Wijaya' }}" readonly>
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold">Lingkup Pemeriksaan</label>
              <select id="opnameScope" class="form-select form-select-sm">
                <option value="Audit Parsial (Fast Moving & High Value)">Audit Parsial (Fast Moving & High Value)</option>
                <option value="Audit Menyeluruh (Full Facility Wall-to-Wall)">Audit Menyeluruh (Full Facility Wall-to-Wall)</option>
                <option value="Audit Zonasi Khusus Karantina / Retur">Audit Zonasi Khusus Karantina / Retur</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold">Catatan Instruksi Audit</label>
              <textarea id="opnameNotes" class="form-control form-control-sm" rows="2" placeholder="Instruksi pembekuan mutasi sementara saat hitung fisik berjalan..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light border-0">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-success">
            <i class="bi bi-check-circle me-1"></i>Jadwalkan Sesi Opname
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ============================================== -->
<!-- MODAL: INPUT HITUNG FISIK (COUNT SHEET)        -->
<!-- ============================================== -->
<div class="modal fade" id="modalInputCount" tabindex="-1" aria-labelledby="modalInputCountLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-info-subtle border-0">
        <h5 class="modal-title fw-bold text-dark" id="modalInputCountLabel">
          <i class="bi bi-pencil-square text-info me-2"></i>Form Input Hitung Fisik (Stock Count Sheet)
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formInputCount" onsubmit="handleCountSubmit(event)">
        <div class="modal-body p-4">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-bold">Pilih Sesi Opname Aktif <span class="text-danger">*</span></label>
              <select id="countSessionSelect" class="form-select form-select-sm" required>
                @foreach($sessions as $s)
                  @if(in_array($s['status'], ['IN_PROGRESS', 'PENDING_APPROVAL', 'DRAFT']))
                    <option value="{{ $s['id'] }}">{{ $s['id'] }} - {{ $s['title'] }} ({{ $s['warehouse'] }})</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Pilih Barang yang Dihitung Fisik <span class="text-danger">*</span></label>
              <select id="countItemSelect" class="form-select form-select-sm" onchange="handleCountItemChange(this)" required>
                <option value="">-- Pilih Barang / SKU --</option>
                @foreach($items as $itm)
                  <option value="{{ $itm['sku'] }}|{{ $itm['name'] }}|{{ $itm['uom'] }}|{{ $itm['bin_location'] }}|{{ $itm['current_stock'] }}|{{ $itm['buy_price'] }}">
                    {{ $itm['sku'] }} - {{ $itm['name'] }} (Saldo Sistem: {{ $itm['current_stock'] }} {{ $itm['uom'] }})
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Live Variance Calculation Box -->
          <div class="card border border-light-subtle rounded-3 p-3 bg-light mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-4 text-center border-end">
                <span class="text-secondary small d-block">Saldo Sistem</span>
                <h4 class="fw-bold text-dark mb-0" id="countSystemQty">0</h4>
                <span class="small text-secondary" id="countUom1">Unit</span>
              </div>
              <div class="col-4 text-center border-end">
                <label class="form-label text-primary small fw-bold d-block mb-1">Hasil Hitung Fisik</label>
                <input type="number" id="countPhysicalQty" class="form-control form-control-sm text-center fw-bold fs-5 mx-auto" style="max-width: 120px;" min="0" value="0" oninput="calculateVariance()" required>
                <span class="small text-secondary" id="countUom2">Unit</span>
              </div>
              <div class="col-4 text-center">
                <span class="text-secondary small d-block">Selisih (Variance)</span>
                <h4 class="fw-bold mb-0 text-success" id="countVarianceResult">0</h4>
                <span class="badge text-bg-success mt-1" id="countVarianceBadge">Cocok (Match)</span>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-bold">Alasan / Temuan Selisih (Bila ada discrepancy)</label>
            <input type="text" id="countReason" class="form-control form-control-sm" placeholder="Contoh: Unit cacat kemasan, barang salah letak rak, atau selisih potong...">
          </div>
        </div>
        <div class="modal-footer bg-light border-0">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-primary">
            <i class="bi bi-save me-1"></i>Simpan Hasil Hitung
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ============================================== -->
<!-- MODAL: DETAIL HASIL AUDIT & SELISIH            -->
<!-- ============================================== -->
<div class="modal fade" id="modalDetailOpname" tabindex="-1" aria-labelledby="modalDetailOpnameLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light border-0">
        <div>
          <h5 class="modal-title fw-bold text-dark mb-0" id="detailOpnameTitle">Detail Sesi Stock Opname</h5>
          <span class="text-secondary small" id="detailOpnameSubtitle">Hasil perbandingan saldo fisik vs sistem</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <!-- Metadata Header -->
        <div class="row g-3 p-3 bg-light rounded-3 mb-4">
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">ID Sesi</span>
            <strong class="text-dark" id="detailOpnameId">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Tanggal Pelaksanaan</span>
            <strong class="text-dark" id="detailOpnameDate">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Fasilitas Gudang</span>
            <strong class="text-dark" id="detailOpnameWarehouse">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Auditor Pelaksana</span>
            <strong class="text-dark" id="detailOpnameAuditor">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Total Diperiksa</span>
            <strong class="text-dark" id="detailOpnameTotalChecked">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Jumlah Cocok</span>
            <strong class="text-success" id="detailOpnameMatchCount">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Jumlah Selisih</span>
            <strong class="text-danger" id="detailOpnameDiscrepancyCount">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Status Sesi</span>
            <span class="badge" id="detailOpnameStatusBadge">-</span>
          </div>
        </div>

        <h6 class="fw-bold text-dark mb-2">Tabel Hasil Perhitungan Fisik per Item:</h6>
        <div class="table-responsive mb-3">
          <table class="table table-sm table-bordered align-middle">
            <thead class="table-light small text-secondary">
              <tr>
                <th>No</th>
                <th>SKU & Barang</th>
                <th>Rak</th>
                <th class="text-center">Sistem</th>
                <th class="text-center">Fisik</th>
                <th class="text-center">Selisih</th>
                <th class="text-end">Nilai Selisih</th>
                <th>Alasan / Keterangan</th>
              </tr>
            </thead>
            <tbody id="detailOpnameItemsBody" class="small">
            </tbody>
          </table>
        </div>

        <div class="p-3 bg-light-subtle border rounded-2">
          <div class="fw-bold small text-dark mb-1"><i class="bi bi-chat-left-text me-1 text-secondary"></i>Catatan Auditor:</div>
          <p class="small text-secondary mb-0" id="detailOpnameNotes">-</p>
        </div>
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="triggerPrintFromDetail()">
          <i class="bi bi-printer me-1"></i>Cetak Berita Acara (BAST)
        </button>
        <span id="detailOpnameApproveBtnContainer"></span>
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- ============================================== -->
<!-- MODAL: PREVIEW CETAK BERITA ACARA OPNAME (BAST)-->
<!-- ============================================== -->
<div class="modal fade" id="modalPrintBAST" tabindex="-1" aria-labelledby="modalPrintBASTLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light border-0">
        <h5 class="modal-title fw-bold text-dark" id="modalPrintBASTLabel">
          <i class="bi bi-printer text-secondary me-2"></i>Preview Cetak Berita Acara Hasil Stock Opname (BAST)
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-white">
        <div class="border p-4 rounded-2 bg-white text-dark" style="font-family: Arial, sans-serif;">
          <!-- Header BAST -->
          <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <div>
              <h5 class="fw-bold text-dark mb-0">PT LOGISTIK MAJU SENTOSA</h5>
              <div class="small text-secondary">Kawasan Industri Cikarang Blok B-12, Bekasi, Jawa Barat</div>
              <div class="small text-secondary">Divisi Pengendalian Mutu & Audit Persediaan Logistik</div>
            </div>
            <div class="text-end">
              <span class="badge text-bg-success fs-6 px-3 py-2 mb-1 fw-bold">BERITA ACARA AUDIT</span>
              <div class="fw-bold fs-5 text-dark" id="printBASTId">OPN-2026-11-01</div>
            </div>
          </div>

          <div class="text-center my-3">
            <h6 class="fw-bold text-uppercase text-decoration-underline mb-1">BERITA ACARA HASIL STOCK OPNAME PERSIDANGAN STOK</h6>
            <span class="small text-secondary" id="printBASTTitle">Stock Opname Awal Bulan November 2026</span>
          </div>

          <!-- Metadata -->
          <div class="row g-2 mb-3 small">
            <div class="col-6">
              <table class="table-borderless w-100">
                <tr>
                  <td class="text-secondary" style="width: 140px;">Tanggal Pelaksanaan</td>
                  <td>: <strong id="printBASTDate">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Lokasi Fasilitas</td>
                  <td>: <strong id="printBASTWarehouse">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Auditor / Pemeriksa</td>
                  <td>: <strong id="printBASTAuditor">-</strong></td>
                </tr>
              </table>
            </div>
            <div class="col-6">
              <table class="table-borderless w-100">
                <tr>
                  <td class="text-secondary" style="width: 140px;">Pejabat Persetujuan</td>
                  <td>: <strong id="printBASTApprover">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Total SKU Diperiksa</td>
                  <td>: <strong id="printBASTTotalChecked">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Status Hasil Audit</td>
                  <td>: <span class="badge text-bg-success" id="printBASTStatus">Selesai & Disetujui</span></td>
                </tr>
              </table>
            </div>
          </div>

          <!-- Table -->
          <table class="table table-sm table-bordered align-middle mb-4">
            <thead class="table-light text-secondary small text-uppercase">
              <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 110px;">SKU</th>
                <th>Deskripsi Barang</th>
                <th class="text-center" style="width: 70px;">Sistem</th>
                <th class="text-center" style="width: 70px;">Fisik</th>
                <th class="text-center" style="width: 80px;">Selisih</th>
                <th class="text-end" style="width: 110px;">Nilai Selisih</th>
                <th>Keterangan / Alasan</th>
              </tr>
            </thead>
            <tbody id="printBASTItemsBody" class="small">
            </tbody>
          </table>

          <div class="small p-2 bg-light border mb-4">
            <strong>Kesimpulan & Tindak Lanjut:</strong> Berdasarkan pemeriksaan fisik di atas, selisih persediaan telah diverifikasi oleh tim auditor dan diusulkan untuk dilakukan jurnal koreksi penyesuaian (*stock adjustment*) ke kartu stok digital.
          </div>

          <!-- Signatures 4 Pihak -->
          <div class="row text-center mt-4 pt-2 small">
            <div class="col-3">
              <p class="text-secondary mb-4">Auditor Lapangan,</p>
              <br>
              <div class="border-top pt-1 text-dark fw-bold" id="printSignAuditor">Hendra Wijaya</div>
            </div>
            <div class="col-3">
              <p class="text-secondary mb-4">Petugas Gudang,</p>
              <br>
              <div class="border-top pt-1 text-dark fw-bold">Joko Prasetyo</div>
            </div>
            <div class="col-3">
              <p class="text-secondary mb-4">Finance / Akuntansi,</p>
              <br>
              <div class="border-top pt-1 text-dark fw-bold" id="printSignFinance">Hendra Wijaya</div>
            </div>
            <div class="col-3">
              <p class="text-secondary mb-4">Kepala Gudang,</p>
              <br>
              <div class="border-top pt-1 text-dark fw-bold">Bambang Sugiarto</div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-sm btn-success" onclick="window.print()">
          <i class="bi bi-printer me-1"></i>Cetak Berita Acara
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Central Opname Dataset
  const opnameDataset = @json($sessions);
  let activeOpnameId = null;

  // Filter & Search Logic
  document.getElementById('opnameSearch')?.addEventListener('input', applyFilters);
  document.getElementById('filterStatus')?.addEventListener('change', applyFilters);
  document.getElementById('filterWarehouse')?.addEventListener('change', applyFilters);

  function applyFilters() {
    const q = document.getElementById('opnameSearch').value.toLowerCase().trim();
    const st = document.getElementById('filterStatus').value;
    const wh = document.getElementById('filterWarehouse').value;

    const rows = document.querySelectorAll('.opname-row');
    let count = 0;

    rows.forEach(r => {
      const id = r.getAttribute('data-id').toLowerCase();
      const title = r.getAttribute('data-title').toLowerCase();
      const warehouse = r.getAttribute('data-warehouse');
      const auditor = r.getAttribute('data-auditor').toLowerCase();
      const status = r.getAttribute('data-status');

      const matchesQ = !q || id.includes(q) || title.includes(q) || auditor.includes(q) || warehouse.toLowerCase().includes(q);
      const matchesSt = !st || status === st;
      const matchesWh = !wh || warehouse === wh;

      if (matchesQ && matchesSt && matchesWh) {
        r.style.display = '';
        count++;
      } else {
        r.style.display = 'none';
      }
    });

    document.getElementById('rowCountInfo').innerText = `Menampilkan ${count} sesi stock opname`;
  }

  function resetFilters() {
    document.getElementById('opnameSearch').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterWarehouse').value = '';
    applyFilters();
    window.showToast('Filter stock opname telah direset', 'info', 'Filter');
  }

  // Detail Modal Handler
  function showDetailOpname(id) {
    const data = opnameDataset.find(s => s.id === id);
    if (!data) return;

    activeOpnameId = id;
    document.getElementById('detailOpnameTitle').innerText = `${data.id} - ${data.title}`;
    document.getElementById('detailOpnameId').innerText = data.id;
    document.getElementById('detailOpnameDate').innerText = data.date;
    document.getElementById('detailOpnameWarehouse').innerText = data.warehouse;
    document.getElementById('detailOpnameAuditor').innerText = data.auditor;
    document.getElementById('detailOpnameTotalChecked').innerText = `${data.total_items_checked} SKU`;
    document.getElementById('detailOpnameMatchCount').innerText = `${data.match_count} SKU Sesuai`;
    document.getElementById('detailOpnameDiscrepancyCount').innerText = `${data.discrepancy_count} SKU Selisih`;
    document.getElementById('detailOpnameNotes').innerText = data.notes || 'Tidak ada catatan khusus.';

    const badge = document.getElementById('detailOpnameStatusBadge');
    badge.className = `badge text-bg-${data.status_badge}`;
    badge.innerText = data.status_label;

    const tbody = document.getElementById('detailOpnameItemsBody');
    tbody.innerHTML = '';

    if (!data.items || data.items.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-3">Belum ada item yang diinput pada sesi ini.</td></tr>`;
    } else {
      data.items.forEach((itm, idx) => {
        const tr = document.createElement('tr');
        const varClass = itm.variance < 0 ? 'text-danger fw-bold' : (itm.variance > 0 ? 'text-info fw-bold' : 'text-success');
        const sign = itm.variance > 0 ? '+' : '';
        tr.innerHTML = `
          <td class="text-center">${idx + 1}</td>
          <td>
            <strong class="text-dark d-block">${itm.name}</strong>
            <span class="font-monospace text-secondary small">${itm.sku}</span>
          </td>
          <td><span class="badge text-bg-light border text-secondary">${itm.bin}</span></td>
          <td class="text-center">${itm.system_qty} ${itm.uom}</td>
          <td class="text-center fw-bold">${itm.physical_qty} ${itm.uom}</td>
          <td class="text-center ${varClass}">${sign}${itm.variance} ${itm.uom}</td>
          <td class="text-end ${varClass}">Rp ${sign}${Math.abs(itm.variance_value).toLocaleString('id-ID')}</td>
          <td><span class="small text-secondary">${itm.reason || '-'}</span></td>
        `;
        tbody.appendChild(tr);
      });
    }

    // Approve Button in Modal
    const approveContainer = document.getElementById('detailOpnameApproveBtnContainer');
    if (data.status === 'PENDING_APPROVAL') {
      approveContainer.innerHTML = `
        <button type="button" class="btn btn-sm btn-success" onclick="quickApproveOpname('${data.id}')">
          <i class="bi bi-check2-circle me-1"></i>Setujui Penyesuaian Saldo (Approve Adjustment)
        </button>
      `;
    } else {
      approveContainer.innerHTML = '';
    }

    const modal = new bootstrap.Modal(document.getElementById('modalDetailOpname'));
    modal.show();
  }

  // Print BAST Modal Handler
  function showPrintBAST(id) {
    const data = opnameDataset.find(s => s.id === id);
    if (!data) return;

    document.getElementById('printBASTId').innerText = data.id;
    document.getElementById('printBASTTitle').innerText = data.title;
    document.getElementById('printBASTDate').innerText = data.date;
    document.getElementById('printBASTWarehouse').innerText = data.warehouse;
    document.getElementById('printBASTAuditor').innerText = data.auditor;
    document.getElementById('printBASTApprover').innerText = data.approver || 'Bambang Sugiarto';
    document.getElementById('printBASTTotalChecked').innerText = `${data.total_items_checked} SKU (${data.match_count} Cocok, ${data.discrepancy_count} Selisih)`;
    document.getElementById('printBASTStatus').innerText = data.status_label;
    document.getElementById('printSignAuditor').innerText = data.auditor;

    const tbody = document.getElementById('printBASTItemsBody');
    tbody.innerHTML = '';

    if (!data.items || data.items.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted">Belum ada baris perhitungan fisik.</td></tr>`;
    } else {
      data.items.forEach((itm, idx) => {
        const sign = itm.variance > 0 ? '+' : '';
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td class="text-center">${idx + 1}</td>
          <td class="font-monospace">${itm.sku}</td>
          <td>${itm.name}</td>
          <td class="text-center">${itm.system_qty} ${itm.uom}</td>
          <td class="text-center fw-bold">${itm.physical_qty} ${itm.uom}</td>
          <td class="text-center">${sign}${itm.variance} ${itm.uom}</td>
          <td class="text-end">Rp ${sign}${Math.abs(itm.variance_value).toLocaleString('id-ID')}</td>
          <td>${itm.reason || 'Sesuai audit fisik'}</td>
        `;
        tbody.appendChild(tr);
      });
    }

    const modal = new bootstrap.Modal(document.getElementById('modalPrintBAST'));
    modal.show();
  }

  function triggerPrintFromDetail() {
    if (activeOpnameId) {
      bootstrap.Modal.getInstance(document.getElementById('modalDetailOpname'))?.hide();
      setTimeout(() => {
        showPrintBAST(activeOpnameId);
      }, 300);
    }
  }

  // Handle Count Item Change
  let currentSelectedPrice = 0;
  function handleCountItemChange(sel) {
    if (!sel.value) return;
    const parts = sel.value.split('|');
    const uom = parts[2] || 'Unit';
    const systemStock = parseInt(parts[4]) || 0;
    currentSelectedPrice = parseInt(parts[5]) || 0;

    document.getElementById('countSystemQty').innerText = systemStock;
    document.getElementById('countPhysicalQty').value = systemStock;
    document.getElementById('countUom1').innerText = uom;
    document.getElementById('countUom2').innerText = uom;
    calculateVariance();
  }

  // Live Variance Calculator
  function calculateVariance() {
    const sys = parseInt(document.getElementById('countSystemQty').innerText) || 0;
    const phys = parseInt(document.getElementById('countPhysicalQty').value) || 0;
    const variance = phys - sys;

    const resEl = document.getElementById('countVarianceResult');
    const badgeEl = document.getElementById('countVarianceBadge');

    if (variance === 0) {
      resEl.className = 'fw-bold mb-0 text-success';
      resEl.innerText = '0';
      badgeEl.className = 'badge text-bg-success mt-1';
      badgeEl.innerText = 'Cocok (Match)';
    } else if (variance < 0) {
      resEl.className = 'fw-bold mb-0 text-danger';
      resEl.innerText = variance;
      badgeEl.className = 'badge text-bg-danger mt-1';
      badgeEl.innerText = `Minus (${Math.abs(variance)}) Selisih Kurang`;
    } else {
      resEl.className = 'fw-bold mb-0 text-info';
      resEl.innerText = `+${variance}`;
      badgeEl.className = 'badge text-bg-info mt-1';
      badgeEl.innerText = `Surplus (+${variance}) Kelebihan Fisik`;
    }
  }

  // Handle Count Sheet Submission
  function handleCountSubmit(e) {
    e.preventDefault();
    const sessionId = document.getElementById('countSessionSelect').value;
    const sel = document.getElementById('countItemSelect').value;
    if (!sel) {
      window.showToast('Pilihlah barang yang dihitung!', 'danger', 'Validasi Gagal');
      return;
    }

    const parts = sel.split('|');
    const sku = parts[0];
    const name = parts[1];
    const uom = parts[2];
    const bin = parts[3];
    const sys = parseInt(parts[4]) || 0;
    const phys = parseInt(document.getElementById('countPhysicalQty').value) || 0;
    const variance = phys - sys;
    const reason = document.getElementById('countReason').value.trim() || (variance === 0 ? 'Perhitungan fisik sesuai kartu stok' : 'Penyesuaian hasil audit fisik');
    const varValue = variance * currentSelectedPrice;

    const session = opnameDataset.find(s => s.id === sessionId);
    if (session) {
      if (!session.items) session.items = [];
      session.items.push({
        sku: sku,
        name: name,
        bin: bin,
        system_qty: sys,
        physical_qty: phys,
        variance: variance,
        uom: uom,
        buy_price: currentSelectedPrice,
        variance_value: varValue,
        status: variance === 0 ? 'Sesuai (Match)' : (variance < 0 ? 'Minus' : 'Surplus'),
        badge: variance === 0 ? 'success' : (variance < 0 ? 'danger' : 'info'),
        reason: reason
      });
      session.total_items_checked++;
      if (variance === 0) {
        session.match_count++;
      } else {
        session.discrepancy_count++;
        session.net_variance_value += varValue;
        session.status = 'PENDING_APPROVAL';
        session.status_label = 'Menunggu Approval Manager / Finance';
        session.status_badge = 'warning';
      }
    }

    bootstrap.Modal.getInstance(document.getElementById('modalInputCount'))?.hide();
    document.getElementById('formInputCount').reset();

    window.showToast(`Hasil hitung fisik untuk ${sku} (${phys} ${uom}) berhasil dicatat pada sesi ${sessionId}!`, 'success', 'Hitung Fisik Tersimpan');
    showDetailOpname(sessionId);
  }

  // Handle Add Opname Session
  function handleAddOpnameSubmit(e) {
    e.preventDefault();
    const title = document.getElementById('opnameTitle').value.trim();
    const wh = document.getElementById('opnameWarehouse').value;
    const date = document.getElementById('opnameDate').value;
    const auditor = document.getElementById('opnameAuditor').value;
    const notes = document.getElementById('opnameNotes').value.trim();

    const nextId = `OPN-2026-11-${String(opnameDataset.length + 16).padStart(2, '0')}`;

    const newSession = {
      id: nextId,
      title: title,
      date: date,
      warehouse: wh,
      auditor: auditor,
      approver: '-',
      total_items_checked: 0,
      match_count: 0,
      discrepancy_count: 0,
      net_variance_value: 0,
      status: 'IN_PROGRESS',
      status_label: 'Sedang Berjalan (Hitung Fisik)',
      status_badge: 'info',
      notes: notes || 'Sesi audit stok fisik terjadwal.',
      items: []
    };

    opnameDataset.unshift(newSession);

    const tbody = document.querySelector('#opnameTable tbody');
    const tr = document.createElement('tr');
    tr.className = 'opname-row bg-info-subtle';
    tr.setAttribute('data-id', newSession.id);
    tr.setAttribute('data-title', newSession.title);
    tr.setAttribute('data-warehouse', newSession.warehouse);
    tr.setAttribute('data-auditor', newSession.auditor);
    tr.setAttribute('data-status', newSession.status);

    tr.innerHTML = `
      <td class="ps-3">
        <span class="fw-bold text-dark d-block">${newSession.id} <span class="badge text-bg-info ms-1 text-dark">Baru</span></span>
        <span class="text-secondary small"><i class="bi bi-calendar3 me-1"></i>${newSession.date}</span>
      </td>
      <td>
        <span class="fw-semibold text-dark d-block">${newSession.title}</span>
        <span class="text-secondary small fst-italic">${newSession.notes}</span>
      </td>
      <td>
        <span class="text-dark fw-medium d-block">${newSession.warehouse}</span>
        <span class="badge text-bg-secondary-subtle text-secondary border small">Audit Fasilitas</span>
      </td>
      <td>
        <div class="text-dark fw-medium"><i class="bi bi-person-check me-1 text-primary"></i>${newSession.auditor}</div>
        <div class="text-secondary small">Approver: <strong>-</strong></div>
      </td>
      <td class="text-center">
        <span class="badge text-bg-light border text-secondary">0 SKU Diperiksa</span>
      </td>
      <td class="text-end">
        <span class="text-success fw-bold">Rp 0</span>
        <div class="small text-secondary">Nol Selisih</div>
      </td>
      <td class="text-center">
        <span class="badge text-bg-${newSession.status_badge} rounded-pill px-2 py-1">
          ${newSession.status_label}
        </span>
      </td>
      <td class="text-end pe-3">
        <div class="btn-group btn-group-sm">
          <button type="button" class="btn btn-outline-info" title="Lihat Hasil & Selisih" onclick="showDetailOpname('${newSession.id}')">
            <i class="bi bi-eye"></i>
          </button>
          <button type="button" class="btn btn-outline-secondary" title="Cetak Berita Acara (BAST)" onclick="showPrintBAST('${newSession.id}')">
            <i class="bi bi-printer"></i>
          </button>
        </div>
      </td>
    `;

    tbody.insertBefore(tr, tbody.firstChild);

    bootstrap.Modal.getInstance(document.getElementById('modalAddOpname'))?.hide();
    document.getElementById('formAddOpname').reset();
    applyFilters();

    window.showToast(`Sesi stock opname ${newSession.id} berhasil dijadwalkan!`, 'success', 'Sesi Dibuka');
  }

  // Quick Approve Opname Handler
  function quickApproveOpname(id, btnEl = null) {
    const session = opnameDataset.find(s => s.id === id);
    if (!session) return;

    session.status = 'APPROVED';
    session.status_label = 'Selesai & Disetujui (Adjusted)';
    session.status_badge = 'success';
    session.approver = '{{ $currentRole["user_name"] ?? "Bambang Sugiarto" }}';

    // Update row in table
    const row = document.querySelector(`.opname-row[data-id="${id}"]`);
    if (row) {
      row.setAttribute('data-status', 'APPROVED');
      const badge = row.querySelector('td:nth-child(7) span');
      if (badge) {
        badge.className = 'badge text-bg-success rounded-pill px-2 py-1';
        badge.innerText = session.status_label;
      }
      const approverEl = row.querySelector('td:nth-child(4) .text-secondary small strong');
      if (approverEl) approverEl.innerText = session.approver;
    }

    if (btnEl) btnEl.remove();

    // Close detail modal if open
    bootstrap.Modal.getInstance(document.getElementById('modalDetailOpname'))?.hide();

    window.showToast(`Selisih stock opname ${id} telah disetujui & saldo telah disesuaikan (Write-off)!`, 'success', 'Persetujuan Berhasil');
  }

  function exportOpnameData() {
    window.showToast('Mengekspor rekapitulasi audit dan variance stock opname ke format Excel...', 'info', 'Ekspor Data');
  }
</script>
@endpush
