@extends('layouts.app')

@section('title', 'Penerimaan Barang (Goods Receipt)')

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
      <h3 class="mb-1 fw-bold text-dark">
        <i class="bi bi-box-arrow-in-down text-info me-2"></i>Penerimaan Barang (Goods Receipt)
      </h3>
      <p class="text-secondary mb-0 small">
        Pencatatan barang masuk dari pemasok, verifikasi surat jalan, inspeksi fisik QC & penempatan ke rak gudang.
      </p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" onclick="exportInboundData()">
        <i class="bi bi-download me-1"></i>Ekspor Data
      </button>
      @if(\App\Support\RoleManager::can('inbound'))
      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddInbound">
        <i class="bi bi-plus-circle me-1"></i>Catat Penerimaan Baru
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
            <div class="flex-shrink-0 bg-info-subtle text-info p-2 rounded-3 me-3">
              <i class="bi bi-receipt-cutoff fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Total Dokumen GR</div>
              <h5 class="fw-bold mb-0 text-dark">{{ count($receipts) }} Berkas</h5>
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
              <i class="bi bi-check2-circle fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Selesai Masuk Stok</div>
              <h5 class="fw-bold mb-0 text-dark">
                {{ count(array_filter($receipts, fn($r) => $r['status'] === 'COMPLETED')) }} GR
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
              <i class="bi bi-clock-history fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Dalam Inspeksi QC</div>
              <h5 class="fw-bold mb-0 text-dark">
                {{ count(array_filter($receipts, fn($r) => in_array($r['status'], ['RECEIVED', 'INSPECTED']))) }} Dokumen
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
            <div class="flex-shrink-0 bg-primary-subtle text-primary p-2 rounded-3 me-3">
              <i class="bi bi-boxes fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Total Unit Diterima</div>
              <h5 class="fw-bold mb-0 text-dark">
                {{ number_format(array_sum(array_column($receipts, 'total_qty')), 0, ',', '.') }} Unit
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
            <input type="text" id="inboundSearch" class="form-control bg-light border-start-0" placeholder="Cari No. GR, No. PO, Vendor, atau Surat Jalan...">
          </div>
        </div>

        <!-- Filter Status -->
        <div class="col-6 col-md-3">
          <select id="filterStatus" class="form-select form-select-sm">
            <option value="">Semua Status Penerimaan</option>
            <option value="COMPLETED">Selesai (Masuk Stok)</option>
            <option value="INSPECTED">Inspeksi Selesai (Putaway)</option>
            <option value="RECEIVED">Diterima (Proses QC)</option>
            <option value="DRAFT">Draft (Menunggu Truk)</option>
          </select>
        </div>

        <!-- Filter Gudang -->
        <div class="col-6 col-md-3">
          <select id="filterWarehouse" class="form-select form-select-sm">
            <option value="">Semua Gudang Tujuan</option>
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
      <table class="table table-hover align-middle mb-0" id="inboundTable">
        <thead class="table-light text-secondary small text-uppercase">
          <tr>
            <th class="ps-3" style="width: 140px;">No. Dokumen</th>
            <th>Referensi PO & SJ</th>
            <th>Pemasok / Vendor</th>
            <th>Gudang Tujuan</th>
            <th class="text-center">Total Item</th>
            <th>Penerima & QC</th>
            <th class="text-center">Status</th>
            <th class="text-end pe-3" style="width: 130px;">Aksi</th>
          </tr>
        </thead>
        <tbody class="small">
          @forelse($receipts as $receipt)
            <tr class="inbound-row" 
                data-id="{{ $receipt['id'] }}"
                data-po="{{ $receipt['po_number'] }}"
                data-sj="{{ $receipt['delivery_order_no'] }}"
                data-supplier="{{ $receipt['supplier_name'] }}"
                data-warehouse="{{ $receipt['warehouse_name'] }}"
                data-status="{{ $receipt['status'] }}">
              <td class="ps-3">
                <span class="fw-bold text-dark d-block">{{ $receipt['id'] }}</span>
                <span class="text-secondary small"><i class="bi bi-calendar3 me-1"></i>{{ $receipt['date'] }}</span>
              </td>
              <td>
                <span class="badge text-bg-light border text-dark mb-1">
                  <i class="bi bi-file-earmark-text me-1 text-primary"></i>{{ $receipt['po_number'] }}
                </span>
                <div class="text-secondary small">
                  SJ: <strong class="text-dark">{{ $receipt['delivery_order_no'] }}</strong>
                </div>
              </td>
              <td>
                <span class="fw-semibold text-dark d-block">{{ $receipt['supplier_name'] }}</span>
                <span class="text-secondary small"><i class="bi bi-geo-alt me-1"></i>{{ $receipt['supplier_id'] }}</span>
              </td>
              <td>
                <span class="text-dark fw-medium d-block">{{ $receipt['warehouse_name'] }}</span>
                <span class="badge text-bg-secondary-subtle text-secondary border small">{{ $receipt['bin_location'] }}</span>
              </td>
              <td class="text-center">
                <span class="fw-bold text-primary fs-6">{{ count($receipt['items']) }} SKU</span>
                <div class="text-secondary small">{{ number_format($receipt['total_qty'], 0, ',', '.') }} unit</div>
              </td>
              <td>
                <div class="text-dark fw-medium"><i class="bi bi-person-badge me-1 text-secondary"></i>{{ $receipt['receiver'] }}</div>
                <div class="text-secondary small"><i class="bi bi-shield-check me-1 text-success"></i>{{ $receipt['qc_inspector'] }} ({{ $receipt['qc_status'] }})</div>
              </td>
              <td class="text-center">
                <span class="badge text-bg-{{ $receipt['status_badge'] }} rounded-pill px-2 py-1">
                  {{ $receipt['status_label'] }}
                </span>
              </td>
              <td class="text-end pe-3">
                <div class="btn-group btn-group-sm">
                  <button type="button" class="btn btn-outline-info" title="Lihat Rincian GR" onclick="showDetailModal('{{ $receipt['id'] }}')">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button type="button" class="btn btn-outline-secondary" title="Cetak Surat Penerimaan" onclick="showPrintModal('{{ $receipt['id'] }}')">
                    <i class="bi bi-printer"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-4 text-secondary">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>Belum ada data penerimaan barang.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer bg-white border-top-0 py-3">
      <div class="d-flex justify-content-between align-items-center">
        <span class="text-secondary small" id="rowCountInfo">Menampilkan {{ count($receipts) }} dokumen penerimaan</span>
        <span class="badge text-bg-light border text-secondary">Mode Interaktif Prototipe</span>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- MODAL: CATAT PENERIMAAN BARU (INBOUND)     -->
<!-- ========================================== -->
<div class="modal fade" id="modalAddInbound" tabindex="-1" aria-labelledby="modalAddInboundLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-info-subtle border-0">
        <h5 class="modal-title fw-bold text-dark" id="modalAddInboundLabel">
          <i class="bi bi-plus-circle text-info me-2"></i>Catat Penerimaan Barang (Goods Receipt)
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formAddInbound" onsubmit="handleInboundSubmit(event)">
        <div class="modal-body p-4">
          <div class="alert alert-info py-2 small d-flex align-items-center mb-3">
            <i class="bi bi-info-circle-fill fs-5 me-2"></i>
            <div>
              Form ini mensimulasikan pencatatan kedatangan barang dari vendor, pengecekan surat jalan, dan penerbitan nomor Goods Receipt baru.
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-bold">Nomor Surat Jalan Vendor (DO) <span class="text-danger">*</span></label>
              <input type="text" id="inboundSJ" class="form-control form-control-sm" placeholder="Contoh: SJ-MPL-9045" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Referensi Purchase Order (PO) <span class="text-danger">*</span></label>
              <input type="text" id="inboundPO" class="form-control form-control-sm" placeholder="Contoh: PO-2026-118" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Pemasok / Vendor <span class="text-danger">*</span></label>
              <select id="inboundSupplier" class="form-select form-select-sm" required>
                <option value="">-- Pilih Vendor Pemasok --</option>
                @foreach($suppliers as $spl)
                  <option value="{{ $spl['name'] }}">{{ $spl['name'] }} ({{ $spl['category'] }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Gudang Tujuan <span class="text-danger">*</span></label>
              <select id="inboundWarehouse" class="form-select form-select-sm" required>
                <option value="">-- Pilih Gudang Tujuan --</option>
                @foreach($warehouses as $wh)
                  <option value="{{ $wh['name'] }}">{{ $wh['name'] }} ({{ $wh['code'] }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Petugas Penerima (Gudang)</label>
              <input type="text" id="inboundReceiver" class="form-control form-control-sm" value="{{ $currentRole['user_name'] ?? 'Joko Prasetyo' }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Status Awal</label>
              <select id="inboundInitialStatus" class="form-select form-select-sm">
                <option value="RECEIVED">RECEIVED (Diterima, Masuk Antrian QC)</option>
                <option value="INSPECTED">INSPECTED (QC Langsung Selesai)</option>
                <option value="COMPLETED">COMPLETED (Langsung Masuk Stok)</option>
              </select>
            </div>
          </div>

          <!-- Item Table -->
          <div class="card border border-light-subtle rounded-2 mb-3">
            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
              <span class="small fw-bold text-dark"><i class="bi bi-box-seam me-1"></i>Rincian Barang yang Diterima</span>
              <button type="button" class="btn btn-xs btn-outline-primary" onclick="addItemRow()">
                <i class="bi bi-plus-lg me-1"></i>Tambah Baris
              </button>
            </div>
            <div class="table-responsive p-0">
              <table class="table table-sm table-bordered align-middle mb-0" id="itemInputTable">
                <thead class="table-light small text-secondary">
                  <tr>
                    <th style="width: 40%;">Barang / SKU</th>
                    <th style="width: 20%;">Qty Dipesan</th>
                    <th style="width: 20%;">Qty Diterima</th>
                    <th style="width: 15%;">Kondisi</th>
                    <th style="width: 5%;"></th>
                  </tr>
                </thead>
                <tbody id="itemInputBody" class="small">
                  <tr>
                    <td>
                      <select class="form-select form-select-sm item-select" required>
                        <option value="">-- Pilih SKU Barang --</option>
                        @foreach($items as $itm)
                          <option value="{{ $itm['sku'] }}|{{ $itm['name'] }}|{{ $itm['uom'] }}">
                            {{ $itm['sku'] }} - {{ $itm['name'] }} ({{ $itm['uom'] }})
                          </option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <input type="number" class="form-control form-control-sm item-qty-ordered" min="1" value="10" required>
                    </td>
                    <td>
                      <input type="number" class="form-control form-control-sm item-qty-received" min="0" value="10" required>
                    </td>
                    <td>
                      <select class="form-select form-select-sm item-condition">
                        <option value="Baik">Baik</option>
                        <option value="Kemasan Rusak">Kemasan Rusak</option>
                        <option value="Cacat Fisik">Cacat Fisik</option>
                      </select>
                    </td>
                    <td class="text-center">
                      <button type="button" class="btn btn-sm text-danger p-0 border-0" onclick="removeItemRow(this)">
                        <i class="bi bi-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-bold">Catatan Penerimaan / Keterangan Dokumen</label>
            <textarea id="inboundNotes" class="form-control form-control-sm" rows="2" placeholder="Catatan kondisi kontainer, nomor polisi truk armada, atau rincian sampling..."></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light border-0">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-primary">
            <i class="bi bi-check-circle me-1"></i>Simpan Penerimaan Barang
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- MODAL: RINCIAN DETAIL GOODS RECEIPT        -->
<!-- ========================================== -->
<div class="modal fade" id="modalDetailInbound" tabindex="-1" aria-labelledby="modalDetailInboundLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light border-0">
        <div>
          <h5 class="modal-title fw-bold text-dark mb-0" id="detailGRTitle">Detail Goods Receipt</h5>
          <span class="text-secondary small" id="detailGRSubtitle">Informasi lengkap transaksi penerimaan</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <!-- Header Info Grid -->
        <div class="row g-3 p-3 bg-light rounded-3 mb-4">
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Nomor Dokumen</span>
            <strong class="text-dark" id="detailGRId">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Tanggal Masuk</span>
            <strong class="text-dark" id="detailGRDate">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">No. PO</span>
            <span class="badge text-bg-primary-subtle text-primary border" id="detailGRPo">-</span>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Surat Jalan (DO)</span>
            <strong class="text-dark" id="detailGRSj">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Vendor / Supplier</span>
            <strong class="text-dark" id="detailGRSupplier">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Gudang & Penempatan</span>
            <strong class="text-dark" id="detailGRWarehouse">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Petugas Penerima</span>
            <strong class="text-dark" id="detailGRReceiver">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Status Dokumen</span>
            <span class="badge" id="detailGRStatusBadge">-</span>
          </div>
        </div>

        <h6 class="fw-bold text-dark mb-2">Daftar Barang Diterima:</h6>
        <div class="table-responsive mb-3">
          <table class="table table-sm table-bordered align-middle">
            <thead class="table-light small text-secondary">
              <tr>
                <th>No</th>
                <th>SKU & Nama Barang</th>
                <th class="text-center">Qty PO</th>
                <th class="text-center">Qty Diterima</th>
                <th class="text-center">Kondisi</th>
                <th>Penempatan Rak</th>
                <th>Catatan QC</th>
              </tr>
            </thead>
            <tbody id="detailGRItemsBody" class="small">
              <!-- Dynamically populated -->
            </tbody>
          </table>
        </div>

        <div class="p-3 bg-light-subtle border rounded-2">
          <div class="fw-bold small text-dark mb-1"><i class="bi bi-chat-left-text me-1 text-secondary"></i>Catatan Operasional:</div>
          <p class="small text-secondary mb-0" id="detailGRNotes">-</p>
        </div>
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="triggerPrintFromDetail()">
          <i class="bi bi-printer me-1"></i>Cetak Surat Penerimaan
        </button>
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- MODAL: CETAK BUKTI PENERIMAAN BARANG       -->
<!-- ========================================== -->
<div class="modal fade" id="modalPrintReceipt" tabindex="-1" aria-labelledby="modalPrintReceiptLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light border-0">
        <h5 class="modal-title fw-bold text-dark" id="modalPrintReceiptLabel">
          <i class="bi bi-printer text-secondary me-2"></i>Preview Cetak Bukti Penerimaan Barang
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-white" id="printableArea">
        <!-- Printable Slip Sheet -->
        <div class="border p-4 rounded-2 bg-white text-dark" style="font-family: Arial, sans-serif;">
          <!-- Slip Header -->
          <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <div>
              <h5 class="fw-bold text-dark mb-0">PT LOGISTIK MAJU SENTOSA</h5>
              <div class="small text-secondary">Kawasan Industri Cikarang Blok B-12, Bekasi, Jawa Barat</div>
              <div class="small text-secondary">Telp: (021) 8983-4455 | Email: wh-cikarang@logistikmaju.co.id</div>
            </div>
            <div class="text-end">
              <span class="badge text-bg-dark fs-6 px-3 py-2 mb-1">BUKTI PENERIMAAN BARANG</span>
              <div class="fw-bold fs-5 text-primary" id="printDocNo">GR-2026-004</div>
            </div>
          </div>

          <!-- Slip Meta Grid -->
          <div class="row g-2 mb-3 small">
            <div class="col-6">
              <table class="table-borderless w-100">
                <tr>
                  <td class="text-secondary" style="width: 130px;">Tanggal Terima</td>
                  <td>: <strong id="printDate">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Pemasok / Vendor</td>
                  <td>: <strong id="printSupplier">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Gudang Penerima</td>
                  <td>: <span id="printWarehouse">-</span></td>
                </tr>
              </table>
            </div>
            <div class="col-6">
              <table class="table-borderless w-100">
                <tr>
                  <td class="text-secondary" style="width: 130px;">No. Purchase Order</td>
                  <td>: <strong id="printPo">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">No. Surat Jalan Vendor</td>
                  <td>: <strong id="printSj">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Status Inspeksi</td>
                  <td>: <span class="badge text-bg-success" id="printQcStatus">Passed QC</span></td>
                </tr>
              </table>
            </div>
          </div>

          <!-- Items Table -->
          <table class="table table-sm table-bordered align-middle mb-4">
            <thead class="table-light text-secondary small text-uppercase">
              <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 120px;">SKU</th>
                <th>Deskripsi Barang</th>
                <th class="text-center" style="width: 80px;">Qty PO</th>
                <th class="text-center" style="width: 90px;">Diterima</th>
                <th class="text-center" style="width: 70px;">Satuan</th>
                <th style="width: 110px;">Lokasi Rak</th>
                <th style="width: 90px;">Kondisi</th>
              </tr>
            </thead>
            <tbody id="printItemsBody" class="small">
              <!-- Dynamically generated -->
            </tbody>
          </table>

          <!-- Signatures Section -->
          <div class="row text-center mt-4 pt-2 small">
            <div class="col-3">
              <p class="text-secondary mb-4">Pengirim / Supir Vendor,</p>
              <br>
              <div class="border-top pt-1 text-muted">( ................................ )</div>
            </div>
            <div class="col-3">
              <p class="text-secondary mb-4">Diterima Petugas Gudang,</p>
              <br>
              <div class="border-top pt-1 text-dark fw-bold" id="printSignReceiver">-</div>
            </div>
            <div class="col-3">
              <p class="text-secondary mb-4">Diperiksa Quality Control,</p>
              <br>
              <div class="border-top pt-1 text-dark fw-bold" id="printSignQc">-</div>
            </div>
            <div class="col-3">
              <p class="text-secondary mb-4">Mengetahui Kepala Gudang,</p>
              <br>
              <div class="border-top pt-1 text-dark fw-bold">Bambang Sugiarto</div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
          <i class="bi bi-printer me-1"></i>Cetak Sekarang
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Central Data Cache
  const inboundDataset = @json($receipts);
  let activeInboundId = null;

  // Live Search & Filter Logic
  document.getElementById('inboundSearch').addEventListener('input', applyFilters);
  document.getElementById('filterStatus').addEventListener('change', applyFilters);
  document.getElementById('filterWarehouse').addEventListener('change', applyFilters);

  function applyFilters() {
    const query = document.getElementById('inboundSearch').value.toLowerCase().trim();
    const status = document.getElementById('filterStatus').value;
    const warehouse = document.getElementById('filterWarehouse').value;

    const rows = document.querySelectorAll('.inbound-row');
    let visibleCount = 0;

    rows.forEach(row => {
      const id = row.getAttribute('data-id').toLowerCase();
      const po = row.getAttribute('data-po').toLowerCase();
      const sj = row.getAttribute('data-sj').toLowerCase();
      const supplier = row.getAttribute('data-supplier').toLowerCase();
      const wh = row.getAttribute('data-warehouse');
      const st = row.getAttribute('data-status');

      const matchesQuery = !query || id.includes(query) || po.includes(query) || sj.includes(query) || supplier.includes(query);
      const matchesStatus = !status || st === status;
      const matchesWarehouse = !warehouse || wh === warehouse;

      if (matchesQuery && matchesStatus && matchesWarehouse) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    document.getElementById('rowCountInfo').innerText = `Menampilkan ${visibleCount} dokumen penerimaan`;
  }

  function resetFilters() {
    document.getElementById('inboundSearch').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterWarehouse').value = '';
    applyFilters();
    window.showToast('Semua filter penerimaan telah direset', 'info', 'Filter');
  }

  // Detail Modal Handler
  function showDetailModal(id) {
    const data = inboundDataset.find(item => item.id === id);
    if (!data) return;

    activeInboundId = id;
    document.getElementById('detailGRTitle').innerText = `Goods Receipt: ${data.id}`;
    document.getElementById('detailGRId').innerText = data.id;
    document.getElementById('detailGRDate').innerText = data.date;
    document.getElementById('detailGRPo').innerText = data.po_number;
    document.getElementById('detailGRSj').innerText = data.delivery_order_no;
    document.getElementById('detailGRSupplier').innerText = data.supplier_name;
    document.getElementById('detailGRWarehouse').innerText = `${data.warehouse_name} (${data.bin_location})`;
    document.getElementById('detailGRReceiver').innerText = data.receiver;
    document.getElementById('detailGRNotes').innerText = data.notes || 'Tidak ada catatan khusus.';

    const badgeEl = document.getElementById('detailGRStatusBadge');
    badgeEl.className = `badge text-bg-${data.status_badge}`;
    badgeEl.innerText = data.status_label;

    const tbody = document.getElementById('detailGRItemsBody');
    tbody.innerHTML = '';

    data.items.forEach((item, index) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="text-center">${index + 1}</td>
        <td>
          <span class="fw-bold text-dark d-block">${item.name}</span>
          <span class="text-secondary small font-monospace">${item.sku}</span>
        </td>
        <td class="text-center">${item.qty_ordered} ${item.uom}</td>
        <td class="text-center fw-bold text-success">${item.qty_received} ${item.uom}</td>
        <td class="text-center"><span class="badge text-bg-light border text-dark">${item.condition}</span></td>
        <td><span class="badge text-bg-secondary-subtle text-secondary border">${item.bin}</span></td>
        <td><span class="text-secondary small">${item.qc_note || '-'}</span></td>
      `;
      tbody.appendChild(tr);
    });

    const modal = new bootstrap.Modal(document.getElementById('modalDetailInbound'));
    modal.show();
  }

  // Print Slip Modal Handler
  function showPrintModal(id) {
    const data = inboundDataset.find(item => item.id === id);
    if (!data) return;

    document.getElementById('printDocNo').innerText = data.id;
    document.getElementById('printDate').innerText = data.date;
    document.getElementById('printSupplier').innerText = data.supplier_name;
    document.getElementById('printWarehouse').innerText = data.warehouse_name;
    document.getElementById('printPo').innerText = data.po_number;
    document.getElementById('printSj').innerText = data.delivery_order_no;
    document.getElementById('printQcStatus').innerText = data.qc_status || 'Passed QC';
    document.getElementById('printSignReceiver').innerText = data.receiver;
    document.getElementById('printSignQc').innerText = data.qc_inspector || 'QC Inspector';

    const tbody = document.getElementById('printItemsBody');
    tbody.innerHTML = '';

    data.items.forEach((item, index) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="text-center">${index + 1}</td>
        <td class="font-monospace">${item.sku}</td>
        <td>${item.name}</td>
        <td class="text-center">${item.qty_ordered}</td>
        <td class="text-center fw-bold">${item.qty_received}</td>
        <td class="text-center">${item.uom}</td>
        <td>${item.bin}</td>
        <td class="text-center">${item.condition}</td>
      `;
      tbody.appendChild(tr);
    });

    const modal = new bootstrap.Modal(document.getElementById('modalPrintReceipt'));
    modal.show();
  }

  function triggerPrintFromDetail() {
    if (activeInboundId) {
      bootstrap.Modal.getInstance(document.getElementById('modalDetailInbound'))?.hide();
      setTimeout(() => {
        showPrintModal(activeInboundId);
      }, 300);
    }
  }

  // Add Item Row in New Receipt Form
  function addItemRow() {
    const tableBody = document.getElementById('itemInputBody');
    const firstRow = tableBody.querySelector('tr');
    if (!firstRow) return;

    const newRow = firstRow.cloneNode(true);
    newRow.querySelector('.item-select').value = '';
    newRow.querySelector('.item-qty-ordered').value = '10';
    newRow.querySelector('.item-qty-received').value = '10';
    newRow.querySelector('.item-condition').value = 'Baik';
    tableBody.appendChild(newRow);
  }

  function removeItemRow(btn) {
    const rows = document.querySelectorAll('#itemInputBody tr');
    if (rows.length > 1) {
      btn.closest('tr').remove();
    } else {
      window.showToast('Minimal harus ada satu baris item barang', 'warning', 'Peringatan');
    }
  }

  // Handle Form Submission
  function handleInboundSubmit(event) {
    event.preventDefault();

    const sj = document.getElementById('inboundSJ').value.trim();
    const po = document.getElementById('inboundPO').value.trim();
    const supplier = document.getElementById('inboundSupplier').value;
    const warehouse = document.getElementById('inboundWarehouse').value;
    const receiver = document.getElementById('inboundReceiver').value;
    const status = document.getElementById('inboundInitialStatus').value;
    const notes = document.getElementById('inboundNotes').value.trim();

    // Generate new mock ID
    const nextNum = inboundDataset.length + 5;
    const newId = `GR-2026-${String(nextNum).padStart(3, '0')}`;
    const now = new Date();
    const dateStr = now.toISOString().slice(0, 10) + ' ' + now.toTimeString().slice(0, 5);

    // Build items
    const rows = document.querySelectorAll('#itemInputBody tr');
    const items = [];
    let totalQty = 0;

    rows.forEach(r => {
      const sel = r.querySelector('.item-select').value;
      if (!sel) return;
      const parts = sel.split('|');
      const qtyOrd = parseInt(r.querySelector('.item-qty-ordered').value) || 0;
      const qtyRec = parseInt(r.querySelector('.item-qty-received').value) || 0;
      const cond = r.querySelector('.item-condition').value;

      totalQty += qtyRec;
      items.push({
        sku: parts[0],
        name: parts[1],
        qty_ordered: qtyOrd,
        qty_received: qtyRec,
        qty_rejected: 0,
        uom: parts[2] || 'Unit',
        bin: 'Staging Area',
        condition: cond,
        qc_note: 'Pemeriksaan awal fisik'
      });
    });

    if (items.length === 0) {
      window.showToast('Pilihlah minimal satu SKU barang!', 'danger', 'Validasi Gagal');
      return;
    }

    const newReceipt = {
      id: newId,
      po_number: po,
      delivery_order_no: sj,
      date: dateStr,
      supplier_name: supplier,
      warehouse_name: warehouse,
      bin_location: 'Staging Area (Bay 01)',
      receiver: receiver,
      qc_inspector: 'Sedang Proses QC',
      qc_status: 'Pemeriksaan Sampel',
      status: status,
      status_label: status === 'COMPLETED' ? 'Selesai & Masuk Stok' : (status === 'INSPECTED' ? 'Inspeksi Selesai (Putaway)' : 'Diterima (Proses QC)'),
      status_badge: status === 'COMPLETED' ? 'success' : (status === 'INSPECTED' ? 'primary' : 'warning'),
      notes: notes || 'Penerimaan barang baru dari armada vendor.',
      total_qty: totalQty,
      items: items
    };

    // Prepend to dataset
    inboundDataset.unshift(newReceipt);

    // Prepend row to table
    const tableBody = document.querySelector('#inboundTable tbody');
    const newTr = document.createElement('tr');
    newTr.className = 'inbound-row bg-warning-subtle';
    newTr.setAttribute('data-id', newReceipt.id);
    newTr.setAttribute('data-po', newReceipt.po_number);
    newTr.setAttribute('data-sj', newReceipt.delivery_order_no);
    newTr.setAttribute('data-supplier', newReceipt.supplier_name);
    newTr.setAttribute('data-warehouse', newReceipt.warehouse_name);
    newTr.setAttribute('data-status', newReceipt.status);

    newTr.innerHTML = `
      <td class="ps-3">
        <span class="fw-bold text-dark d-block">${newReceipt.id} <span class="badge text-bg-danger ms-1">Baru</span></span>
        <span class="text-secondary small"><i class="bi bi-calendar3 me-1"></i>${newReceipt.date}</span>
      </td>
      <td>
        <span class="badge text-bg-light border text-dark mb-1">
          <i class="bi bi-file-earmark-text me-1 text-primary"></i>${newReceipt.po_number}
        </span>
        <div class="text-secondary small">SJ: <strong class="text-dark">${newReceipt.delivery_order_no}</strong></div>
      </td>
      <td>
        <span class="fw-semibold text-dark d-block">${newReceipt.supplier_name}</span>
        <span class="text-secondary small"><i class="bi bi-geo-alt me-1"></i>Staging Bay</span>
      </td>
      <td>
        <span class="text-dark fw-medium d-block">${newReceipt.warehouse_name}</span>
        <span class="badge text-bg-secondary-subtle text-secondary border small">${newReceipt.bin_location}</span>
      </td>
      <td class="text-center">
        <span class="fw-bold text-primary fs-6">${newReceipt.items.length} SKU</span>
        <div class="text-secondary small">${newReceipt.total_qty} unit</div>
      </td>
      <td>
        <div class="text-dark fw-medium"><i class="bi bi-person-badge me-1 text-secondary"></i>${newReceipt.receiver}</div>
        <div class="text-secondary small"><i class="bi bi-shield-check me-1 text-warning"></i>${newReceipt.qc_inspector}</div>
      </td>
      <td class="text-center">
        <span class="badge text-bg-${newReceipt.status_badge} rounded-pill px-2 py-1">
          ${newReceipt.status_label}
        </span>
      </td>
      <td class="text-end pe-3">
        <div class="btn-group btn-group-sm">
          <button type="button" class="btn btn-outline-info" title="Lihat Rincian GR" onclick="showDetailModal('${newReceipt.id}')">
            <i class="bi bi-eye"></i>
          </button>
          <button type="button" class="btn btn-outline-secondary" title="Cetak Surat Penerimaan" onclick="showPrintModal('${newReceipt.id}')">
            <i class="bi bi-printer"></i>
          </button>
        </div>
      </td>
    `;

    tableBody.insertBefore(newTr, tableBody.firstChild);

    // Hide Modal & Show Toast
    bootstrap.Modal.getInstance(document.getElementById('modalAddInbound'))?.hide();
    document.getElementById('formAddInbound').reset();
    applyFilters();

    window.showToast(`Penerimaan ${newReceipt.id} berhasil dicatat dan status masuk ke "${newReceipt.status_label}"!`, 'success', 'Penerimaan Berhasil');
  }

  function exportInboundData() {
    window.showToast('Mengekspor 6 dokumen penerimaan barang ke format Excel (CSV)...', 'info', 'Ekspor Data');
  }
</script>
@endpush
