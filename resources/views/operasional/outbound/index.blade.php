@extends('layouts.app')

@section('title', 'Pengeluaran & Transfer Stok (Outbound & Stock Transfer)')

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
      <h3 class="mb-1 fw-bold text-dark">
        <i class="bi bi-box-arrow-up-right text-warning me-2"></i>Pengeluaran & Transfer Stok
      </h3>
      <p class="text-secondary mb-0 small">
        Manajemen penerbitan Delivery Order (DO), pengeluaran barang internal/eksternal, serta mutasi antar fasilitas gudang & rak.
      </p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" onclick="exportOutboundData()">
        <i class="bi bi-download me-1"></i>Ekspor Data
      </button>
      @if(\App\Support\RoleManager::can('outbound'))
      <button class="btn btn-outline-warning text-dark btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#modalAddTransfer">
        <i class="bi bi-arrow-left-right me-1"></i>+ Mutasi Antar Gudang
      </button>
      <button class="btn btn-warning btn-sm text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#modalAddDO">
        <i class="bi bi-plus-circle me-1"></i>+ Buat Surat Jalan (DO)
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
            <div class="flex-shrink-0 bg-warning-subtle text-warning-emphasis p-2 rounded-3 me-3">
              <i class="bi bi-truck fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Total Dokumen DO</div>
              <h5 class="fw-bold mb-0 text-dark">{{ count($outboundOrders) }} Pengiriman</h5>
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
              <div class="text-secondary small fw-medium">Terkirim / Selesai</div>
              <h5 class="fw-bold mb-0 text-dark">
                {{ count(array_filter($outboundOrders, fn($d) => $d['status'] === 'DELIVERED')) }} DO Selesai
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
              <i class="bi bi-arrow-left-right fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Mutasi Antar Gudang</div>
              <h5 class="fw-bold mb-0 text-dark">{{ count($stockTransfers) }} Transfer</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0 bg-danger-subtle text-danger p-2 rounded-3 me-3">
              <i class="bi bi-hourglass-split fs-4"></i>
            </div>
            <div>
              <div class="text-secondary small fw-medium">Menunggu Persetujuan</div>
              <h5 class="fw-bold mb-0 text-danger">
                {{ count(array_filter($outboundOrders, fn($d) => $d['status'] === 'PENDING_APPROVAL')) + count(array_filter($stockTransfers, fn($t) => $t['status'] === 'REQUESTED')) }} Dokumen
              </h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab Navigation -->
  <div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
      <ul class="nav nav-tabs card-header-tabs" id="outboundTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active fw-bold text-dark" id="do-tab" data-bs-toggle="tab" data-bs-target="#do-content" type="button" role="tab" aria-controls="do-content" aria-selected="true">
            <i class="bi bi-send-check text-warning me-1"></i>Pengeluaran Barang (Delivery Order)
            <span class="badge text-bg-warning ms-1 text-dark">{{ count($outboundOrders) }}</span>
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-bold text-secondary" id="transfer-tab" data-bs-toggle="tab" data-bs-target="#transfer-content" type="button" role="tab" aria-controls="transfer-content" aria-selected="false">
            <i class="bi bi-arrow-left-right text-info me-1"></i>Transfer Stok Antar Fasilitas & Rak
            <span class="badge text-bg-info ms-1">{{ count($stockTransfers) }}</span>
          </button>
        </li>
      </ul>
    </div>

    <div class="card-body p-0">
      <div class="tab-content" id="outboundTabsContent">

        <!-- ============================================== -->
        <!-- TAB 1: PENGELUARAN BARANG (DELIVERY ORDER)    -->
        <!-- ============================================== -->
        <div class="tab-pane fade show active" id="do-content" role="tabpanel" aria-labelledby="do-tab">
          <!-- Filter DO Bar -->
          <div class="p-3 bg-light border-bottom">
            <div class="row g-2 align-items-center">
              <div class="col-12 col-md-5">
                <div class="input-group input-group-sm">
                  <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-secondary"></i></span>
                  <input type="text" id="doSearch" class="form-control bg-white border-start-0" placeholder="Cari No. DO, No. Referensi, Customer, atau Divisi...">
                </div>
              </div>
              <div class="col-6 col-md-3">
                <select id="doFilterStatus" class="form-select form-select-sm">
                  <option value="">Semua Status DO</option>
                  <option value="DELIVERED">DELIVERED (Terkirim & Selesai)</option>
                  <option value="SHIPPED">SHIPPED (Dalam Perjalanan)</option>
                  <option value="PACKING">PACKING (Sedang Dikemas)</option>
                  <option value="PENDING_APPROVAL">PENDING_APPROVAL (Menunggu Approval)</option>
                </select>
              </div>
              <div class="col-6 col-md-2">
                <select id="doFilterType" class="form-select form-select-sm">
                  <option value="">Semua Tipe Tujuan</option>
                  <option value="Customer Eksternal">Customer Eksternal</option>
                  <option value="Internal Production">Internal Production</option>
                  <option value="Internal Project">Internal Project</option>
                </select>
              </div>
              <div class="col-12 col-md-2 text-md-end">
                <button class="btn btn-sm btn-outline-secondary w-100" onclick="resetDOFilters()">
                  <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </button>
              </div>
            </div>
          </div>

          <!-- DO Table -->
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="doTable">
              <thead class="table-light text-secondary small text-uppercase">
                <tr>
                  <th class="ps-3" style="width: 140px;">No. Surat Jalan</th>
                  <th>Referensi Order</th>
                  <th>Tujuan / Penerima</th>
                  <th>Gudang Asal</th>
                  <th class="text-center">Total Item</th>
                  <th>Petugas & Pengirim</th>
                  <th class="text-center">Status</th>
                  <th class="text-end pe-3" style="width: 130px;">Aksi</th>
                </tr>
              </thead>
              <tbody class="small">
                @forelse($outboundOrders as $do)
                  <tr class="do-row"
                      data-id="{{ $do['id'] }}"
                      data-ref="{{ $do['reference_no'] }}"
                      data-dest="{{ $do['destination'] }}"
                      data-type="{{ $do['destination_type'] }}"
                      data-status="{{ $do['status'] }}">
                    <td class="ps-3">
                      <span class="fw-bold text-dark d-block">{{ $do['id'] }}</span>
                      <span class="text-secondary small"><i class="bi bi-clock me-1"></i>{{ $do['date'] }}</span>
                    </td>
                    <td>
                      <span class="badge text-bg-light border text-dark">
                        <i class="bi bi-file-earmark-check text-warning me-1"></i>{{ $do['reference_no'] }}
                      </span>
                    </td>
                    <td>
                      <span class="fw-semibold text-dark d-block">{{ $do['destination'] }}</span>
                      <span class="badge text-bg-secondary-subtle text-secondary small border">{{ $do['destination_type'] }}</span>
                    </td>
                    <td>
                      <span class="text-dark d-block">{{ $do['warehouse_origin'] }}</span>
                      <span class="text-secondary small">Staging Bay Keluar</span>
                    </td>
                    <td class="text-center">
                      <span class="fw-bold text-warning-emphasis fs-6">{{ count($do['items']) }} SKU</span>
                      <div class="text-secondary small">{{ $do['total_qty'] }} unit</div>
                    </td>
                    <td>
                      <div class="text-dark fw-medium">{{ $do['operator'] }}</div>
                      <div class="text-secondary small"><i class="bi bi-truck me-1"></i>{{ $do['driver'] }}</div>
                    </td>
                    <td class="text-center">
                      <span class="badge text-bg-{{ $do['status_badge'] }} rounded-pill px-2 py-1">
                        {{ $do['status_label'] }}
                      </span>
                    </td>
                    <td class="text-end pe-3">
                      <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-info" title="Lihat Rincian DO" onclick="showDetailDO('{{ $do['id'] }}')">
                          <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" title="Cetak Surat Jalan (DO)" onclick="showPrintDOSlip('{{ $do['id'] }}')">
                          <i class="bi bi-printer"></i>
                        </button>
                        @if($do['status'] === 'PENDING_APPROVAL' && \App\Support\RoleManager::can('outbound'))
                        <button type="button" class="btn btn-outline-success" title="Approve Pengeluaran" onclick="quickApproveDO('{{ $do['id'] }}', this)">
                          <i class="bi bi-check-lg"></i>
                        </button>
                        @endif
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center py-4 text-secondary">Belum ada transaksi pengeluaran barang.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="p-3 bg-white border-top text-secondary small d-flex justify-content-between align-items-center">
            <span id="doCountInfo">Menampilkan {{ count($outboundOrders) }} dokumen Delivery Order</span>
            <span class="badge text-bg-light border text-secondary">Simulasi Alur Pengeluaran & Surat Jalan</span>
          </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 2: TRANSFER & MUTASI ANTAR GUDANG / RAK   -->
        <!-- ============================================== -->
        <div class="tab-pane fade" id="transfer-content" role="tabpanel" aria-labelledby="transfer-tab">
          <!-- Filter Transfer Bar -->
          <div class="p-3 bg-light border-bottom">
            <div class="row g-2 align-items-center">
              <div class="col-12 col-md-5">
                <div class="input-group input-group-sm">
                  <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-secondary"></i></span>
                  <input type="text" id="transferSearch" class="form-control bg-white border-start-0" placeholder="Cari No. Transfer, SKU, Nama Barang, atau Lokasi...">
                </div>
              </div>
              <div class="col-6 col-md-4">
                <select id="filterTransferStatus" class="form-select form-select-sm">
                  <option value="">Semua Status Transfer</option>
                  <option value="COMPLETED">COMPLETED (Selesai & Diterima)</option>
                  <option value="IN_TRANSIT">IN_TRANSIT (Dalam Perjalanan)</option>
                  <option value="REQUESTED">REQUESTED (Menunggu Persetujuan)</option>
                </select>
              </div>
              <div class="col-6 col-md-3 text-md-end">
                <button class="btn btn-sm btn-outline-secondary w-100" onclick="resetTransferFilters()">
                  <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
                </button>
              </div>
            </div>
          </div>

          <!-- Transfer Table -->
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="transferTable">
              <thead class="table-light text-secondary small text-uppercase">
                <tr>
                  <th class="ps-3" style="width: 140px;">No. Mutasi</th>
                  <th>Rute Fasilitas & Rak</th>
                  <th>Barang & SKU</th>
                  <th class="text-center">Kuantitas</th>
                  <th>Armada / Pelaksana</th>
                  <th>Alasan Mutasi</th>
                  <th class="text-center">Status</th>
                  <th class="text-end pe-3" style="width: 130px;">Aksi</th>
                </tr>
              </thead>
              <tbody class="small">
                @forelse($stockTransfers as $tf)
                  <tr class="transfer-row"
                      data-id="{{ $tf['id'] }}"
                      data-sku="{{ $tf['sku'] }}"
                      data-item="{{ $tf['item_name'] }}"
                      data-from="{{ $tf['from_warehouse'] }}"
                      data-to="{{ $tf['to_warehouse'] }}"
                      data-status="{{ $tf['status'] }}">
                    <td class="ps-3">
                      <span class="fw-bold text-dark d-block">{{ $tf['id'] }}</span>
                      <span class="text-secondary small"><i class="bi bi-clock me-1"></i>{{ $tf['date'] }}</span>
                    </td>
                    <td>
                      <div class="d-flex align-items-center gap-1">
                        <div>
                          <strong class="text-dark small d-block">{{ $tf['from_warehouse'] }}</strong>
                          <span class="badge text-bg-light border text-secondary small">{{ $tf['from_bin'] }}</span>
                        </div>
                        <i class="bi bi-arrow-right text-primary mx-1"></i>
                        <div>
                          <strong class="text-primary small d-block">{{ $tf['to_warehouse'] }}</strong>
                          <span class="badge text-bg-info-subtle text-info border small">{{ $tf['to_bin'] }}</span>
                        </div>
                      </div>
                    </td>
                    <td>
                      <span class="fw-semibold text-dark d-block">{{ $tf['item_name'] }}</span>
                      <span class="font-monospace text-secondary small">{{ $tf['sku'] }}</span>
                    </td>
                    <td class="text-center">
                      <span class="fw-bold fs-6 text-dark">{{ $tf['qty'] }}</span>
                      <span class="text-secondary small d-block">{{ $tf['uom'] }}</span>
                    </td>
                    <td>
                      <div class="text-dark fw-medium">{{ $tf['operator'] }}</div>
                      <div class="text-secondary small"><i class="bi bi-truck me-1"></i>{{ $tf['driver'] }}</div>
                    </td>
                    <td>
                      <span class="text-secondary small fst-italic">{{ $tf['reason'] }}</span>
                    </td>
                    <td class="text-center">
                      <span class="badge text-bg-{{ $tf['status_badge'] }} rounded-pill px-2 py-1">
                        {{ $tf['status_label'] }}
                      </span>
                    </td>
                    <td class="text-end pe-3">
                      <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-info" title="Lihat Rincian Mutasi" onclick="showDetailTransfer('{{ $tf['id'] }}')">
                          <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" title="Cetak Surat Mutasi" onclick="showPrintTransferSlip('{{ $tf['id'] }}')">
                          <i class="bi bi-printer"></i>
                        </button>
                        @if($tf['status'] === 'REQUESTED' && \App\Support\RoleManager::can('outbound'))
                        <button type="button" class="btn btn-outline-success" title="Approve & Dispatch" onclick="quickApproveTransfer('{{ $tf['id'] }}', this)">
                          <i class="bi bi-check-lg"></i>
                        </button>
                        @endif
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center py-4 text-secondary">Belum ada riwayat mutasi antar gudang.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="p-3 bg-white border-top text-secondary small d-flex justify-content-between align-items-center">
            <span id="transferCountInfo">Menampilkan {{ count($stockTransfers) }} data mutasi stok</span>
            <span class="badge text-bg-light border text-secondary">Simulasi Mutasi Multi-Gudang</span>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- ============================================== -->
<!-- MODAL: BUAT SURAT JALAN / DO BARU              -->
<!-- ============================================== -->
<div class="modal fade" id="modalAddDO" tabindex="-1" aria-labelledby="modalAddDOLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-warning-subtle border-0">
        <h5 class="modal-title fw-bold text-dark" id="modalAddDOLabel">
          <i class="bi bi-box-arrow-up-right text-warning-emphasis me-2"></i>Penerbitan Delivery Order (DO) Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formAddDO" onsubmit="handleDOSubmit(event)">
        <div class="modal-body p-4">
          <div class="alert alert-warning py-2 small d-flex align-items-center mb-3">
            <i class="bi bi-info-circle-fill fs-5 me-2 text-warning-emphasis"></i>
            <div>
              Form ini mensimulasikan pemotongan alokasi stok untuk pengiriman ke pelanggan eksternal atau permintaan divisi perakitan internal.
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-bold">No. Referensi Pesanan / SO / Request <span class="text-danger">*</span></label>
              <input type="text" id="doRefNo" class="form-control form-control-sm" placeholder="Contoh: SO-2026-055 atau REQ-PRD-109" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Tipe Pengeluaran <span class="text-danger">*</span></label>
              <select id="doType" class="form-select form-select-sm" required>
                <option value="Customer Eksternal">Customer Eksternal (Penjualan)</option>
                <option value="Internal Production">Internal Production (Perakitan)</option>
                <option value="Internal Project">Internal Project (Proyek Pabrik)</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Nama Tujuan / Pelanggan / Divisi <span class="text-danger">*</span></label>
              <input type="text" id="doDestination" class="form-control form-control-sm" placeholder="Contoh: PT Kalbe Farma Tbk atau Divisi CNC" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Gudang Asal Barang <span class="text-danger">*</span></label>
              <select id="doWarehouse" class="form-select form-select-sm" required>
                @foreach($warehouses as $wh)
                  <option value="{{ $wh['name'] }}">{{ $wh['name'] }} ({{ $wh['code'] }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Armada Pengiriman / Supir / Ekspedisi</label>
              <input type="text" id="doDriver" class="form-control form-control-sm" placeholder="Contoh: Truk Logistik (B 9128 UI) atau JNE" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Status Awal</label>
              <select id="doInitialStatus" class="form-select form-select-sm">
                <option value="PACKING">PACKING (Sedang Dikemas di Staging Bay)</option>
                <option value="SHIPPED">SHIPPED (Langsung Berangkat)</option>
                <option value="PENDING_APPROVAL">PENDING_APPROVAL (Menunggu Persetujuan)</option>
              </select>
            </div>
          </div>

          <!-- Items Selection Table -->
          <div class="card border border-light-subtle rounded-2 mb-3">
            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
              <span class="small fw-bold text-dark"><i class="bi bi-boxes me-1"></i>Pilih Barang yang Dikeluarkan</span>
              <button type="button" class="btn btn-xs btn-outline-warning text-dark fw-medium" onclick="addDOItemRow()">
                <i class="bi bi-plus-lg me-1"></i>Tambah Baris
              </button>
            </div>
            <div class="table-responsive p-0">
              <table class="table table-sm table-bordered align-middle mb-0" id="doItemInputTable">
                <thead class="table-light small text-secondary">
                  <tr>
                    <th style="width: 45%;">Barang (Stok Tersedia)</th>
                    <th style="width: 20%;">Qty Keluar</th>
                    <th style="width: 30%;">Keperluan / Alokasi</th>
                    <th style="width: 5%;"></th>
                  </tr>
                </thead>
                <tbody id="doItemInputBody" class="small">
                  <tr>
                    <td>
                      <select class="form-select form-select-sm do-item-select" onchange="validateDOItemStock(this)" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $itm)
                          <option value="{{ $itm['sku'] }}|{{ $itm['name'] }}|{{ $itm['uom'] }}|{{ $itm['current_stock'] }}|{{ $itm['bin_location'] }}">
                            {{ $itm['sku'] }} - {{ $itm['name'] }} (Stok: {{ $itm['current_stock'] }} {{ $itm['uom'] }})
                          </option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <input type="number" class="form-control form-control-sm do-item-qty" min="1" value="2" oninput="validateDOItemStock(this)" required>
                      <div class="stock-warning text-danger small mt-1 d-none">Melebihi stok!</div>
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sm do-item-purpose" placeholder="Alokasi pesanan..." value="Pesanan Pelanggan">
                    </td>
                    <td class="text-center">
                      <button type="button" class="btn btn-sm text-danger p-0 border-0" onclick="removeDOItemRow(this)">
                        <i class="bi bi-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-bold">Instruksi Khusus / Catatan Pengiriman</label>
            <textarea id="doNotes" class="form-control form-control-sm" rows="2" placeholder="Catatan segel keamanan, waktu kirim, atau nomor kontak penerima..."></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light border-0">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-warning text-dark fw-semibold" id="btnSubmitDO">
            <i class="bi bi-check-circle me-1"></i>Terbitkan Surat Jalan (DO)
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ============================================== -->
<!-- MODAL: MUTASI ANTAR GUDANG & RAK               -->
<!-- ============================================== -->
<div class="modal fade" id="modalAddTransfer" tabindex="-1" aria-labelledby="modalAddTransferLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-info-subtle border-0">
        <h5 class="modal-title fw-bold text-dark" id="modalAddTransferLabel">
          <i class="bi bi-arrow-left-right text-info me-2"></i>Form Mutasi & Transfer Antar Gudang
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formAddTransfer" onsubmit="handleTransferSubmit(event)">
        <div class="modal-body p-4">
          <div class="row g-3 mb-3">
            <div class="col-12">
              <label class="form-label small fw-bold">Pilih Barang yang Dimutasi <span class="text-danger">*</span></label>
              <select id="transferSKU" class="form-select form-select-sm" required>
                <option value="">-- Pilih SKU Barang --</option>
                @foreach($items as $itm)
                  <option value="{{ $itm['sku'] }}|{{ $itm['name'] }}|{{ $itm['uom'] }}|{{ $itm['warehouse_name'] }}|{{ $itm['bin_location'] }}|{{ $itm['current_stock'] }}">
                    {{ $itm['sku'] }} - {{ $itm['name'] }} (Di: {{ $itm['warehouse_name'] }} / {{ $itm['bin_location'] }} - Tersedia: {{ $itm['current_stock'] }} {{ $itm['uom'] }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-6">
              <label class="form-label small fw-bold">Gudang & Rak Asal</label>
              <input type="text" id="transferOrigin" class="form-control form-control-sm bg-light" placeholder="Otomatis terisi..." readonly>
            </div>
            <div class="col-6">
              <label class="form-label small fw-bold">Kuantitas Transfer <span class="text-danger">*</span></label>
              <input type="number" id="transferQty" class="form-control form-control-sm" min="1" value="5" required>
            </div>

            <div class="col-12">
              <label class="form-label small fw-bold">Gudang & Fasilitas Tujuan <span class="text-danger">*</span></label>
              <select id="transferDestWarehouse" class="form-select form-select-sm" required>
                @foreach($warehouses as $wh)
                  <option value="{{ $wh['name'] }}">{{ $wh['name'] }} ({{ $wh['city'] }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold">Rak / Bin Tujuan <span class="text-danger">*</span></label>
              <input type="text" id="transferDestBin" class="form-control form-control-sm" placeholder="Contoh: Rak T1-02 atau Staging Bay 01" value="Rak Buffer Staging" required>
            </div>

            <div class="col-6">
              <label class="form-label small fw-bold">Armada Pengangkut</label>
              <input type="text" id="transferVehicle" class="form-control form-control-sm" placeholder="Contoh: Truk Internal B 9021 WX" value="Mobil Box Operasional (B 9201 CD)" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-bold">Petugas / Operator</label>
              <input type="text" id="transferOperator" class="form-control form-control-sm" value="{{ $currentRole['user_name'] ?? 'Joko Prasetyo' }}" readonly>
            </div>

            <div class="col-12">
              <label class="form-label small fw-bold">Alasan Pemindahan Stok</label>
              <textarea id="transferReason" class="form-control form-control-sm" rows="2" placeholder="Alasan pemindahan buffer, permintaan lini staging, atau isolasi QC..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light border-0">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-info text-dark fw-semibold">
            <i class="bi bi-arrow-left-right me-1"></i>Proses Mutasi Antar Gudang
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ============================================== -->
<!-- MODAL: RINCIAN DETAIL DELIVERY ORDER           -->
<!-- ============================================== -->
<div class="modal fade" id="modalDetailDO" tabindex="-1" aria-labelledby="modalDetailDOLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light border-0">
        <div>
          <h5 class="modal-title fw-bold text-dark mb-0" id="detailDOTitle">Detail Delivery Order</h5>
          <span class="text-secondary small" id="detailDOSubtitle">Informasi pengeluaran barang logistik</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3 p-3 bg-light rounded-3 mb-4">
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Nomor Surat Jalan</span>
            <strong class="text-dark" id="detailDOId">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Tanggal Pengeluaran</span>
            <strong class="text-dark" id="detailDODate">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">No. Referensi Order</span>
            <span class="badge text-bg-warning-subtle text-dark border" id="detailDORef">-</span>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Gudang Pengirim</span>
            <strong class="text-dark" id="detailDOWarehouse">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Penerima / Tujuan</span>
            <strong class="text-dark" id="detailDODest">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Kategori Tujuan</span>
            <span class="badge text-bg-light border text-secondary" id="detailDOType">-</span>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Armada Pengirim</span>
            <strong class="text-dark" id="detailDODriver">-</strong>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-secondary small d-block">Status Pengiriman</span>
            <span class="badge" id="detailDOStatusBadge">-</span>
          </div>
        </div>

        <h6 class="fw-bold text-dark mb-2">Item Barang yang Dikeluarkan:</h6>
        <div class="table-responsive mb-3">
          <table class="table table-sm table-bordered align-middle">
            <thead class="table-light small text-secondary">
              <tr>
                <th>No</th>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th class="text-center">Kuantitas</th>
                <th>Rak Asal</th>
                <th>Alokasi / Tujuan</th>
              </tr>
            </thead>
            <tbody id="detailDOItemsBody" class="small">
            </tbody>
          </table>
        </div>

        <div class="p-3 bg-light-subtle border rounded-2">
          <div class="fw-bold small text-dark mb-1"><i class="bi bi-chat-left-text me-1 text-secondary"></i>Catatan Pengiriman:</div>
          <p class="small text-secondary mb-0" id="detailDONotes">-</p>
        </div>
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="triggerPrintFromDetailDO()">
          <i class="bi bi-printer me-1"></i>Cetak Surat Jalan
        </button>
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- ============================================== -->
<!-- MODAL: PREVIEW CETAK SURAT JALAN (DO SLIP)     -->
<!-- ============================================== -->
<div class="modal fade" id="modalPrintDOSlip" tabindex="-1" aria-labelledby="modalPrintDOSlipLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light border-0">
        <h5 class="modal-title fw-bold text-dark" id="modalPrintDOSlipLabel">
          <i class="bi bi-printer text-secondary me-2"></i>Preview Cetak Surat Jalan (Delivery Order)
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-white">
        <div class="border p-4 rounded-2 bg-white text-dark" style="font-family: Arial, sans-serif;">
          <!-- Header Surat Jalan -->
          <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <div>
              <h5 class="fw-bold text-dark mb-0">PT LOGISTIK MAJU SENTOSA</h5>
              <div class="small text-secondary">Kawasan Industri Cikarang Blok B-12, Bekasi, Jawa Barat</div>
              <div class="small text-secondary">Departemen Logistik & Pengiriman Barang Eksternal</div>
            </div>
            <div class="text-end">
              <span class="badge text-bg-warning text-dark fs-6 px-3 py-2 mb-1 fw-bold">SURAT JALAN (DO)</span>
              <div class="fw-bold fs-5 text-dark" id="printDONo">DO-2026-081</div>
            </div>
          </div>

          <!-- Metadata Grid -->
          <div class="row g-2 mb-3 small">
            <div class="col-6">
              <table class="table-borderless w-100">
                <tr>
                  <td class="text-secondary" style="width: 130px;">Tanggal Kirim</td>
                  <td>: <strong id="printDODate">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Tujuan / Penerima</td>
                  <td>: <strong id="printDODest">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Gudang Asal</td>
                  <td>: <span id="printDOWarehouse">-</span></td>
                </tr>
              </table>
            </div>
            <div class="col-6">
              <table class="table-borderless w-100">
                <tr>
                  <td class="text-secondary" style="width: 130px;">No. Referensi</td>
                  <td>: <strong id="printDORef">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Armada / Driver</td>
                  <td>: <strong id="printDODriver">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Status DO</td>
                  <td>: <span class="badge text-bg-success" id="printDOStatus">Terkirim</span></td>
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
                <th class="text-center" style="width: 80px;">Kuantitas</th>
                <th class="text-center" style="width: 70px;">Satuan</th>
                <th style="width: 110px;">Lokasi Rak</th>
                <th>Keperluan</th>
              </tr>
            </thead>
            <tbody id="printDOItemsBody" class="small">
            </tbody>
          </table>

          <!-- Signatures 4 Pihak -->
          <div class="row text-center mt-4 pt-2 small">
            <div class="col-3">
              <p class="text-secondary mb-4">Yang Menyerahkan,</p>
              <br>
              <div class="border-top pt-1 text-dark fw-bold" id="printSignDOSender">Joko Prasetyo</div>
            </div>
            <div class="col-3">
              <p class="text-secondary mb-4">Supir / Pengangkut,</p>
              <br>
              <div class="border-top pt-1 text-muted" id="printSignDODriver">( ................................ )</div>
            </div>
            <div class="col-3">
              <p class="text-secondary mb-4">Penerima Barang,</p>
              <br>
              <div class="border-top pt-1 text-muted">( ................................ )</div>
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

<!-- ============================================== -->
<!-- MODAL: PREVIEW CETAK SURAT MUTASI (TRANSFER)   -->
<!-- ============================================== -->
<div class="modal fade" id="modalPrintTransferSlip" tabindex="-1" aria-labelledby="modalPrintTransferSlipLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light border-0">
        <h5 class="modal-title fw-bold text-dark" id="modalPrintTransferSlipLabel">
          <i class="bi bi-printer text-secondary me-2"></i>Preview Cetak Surat Mutasi Antar Gudang
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-white">
        <div class="border p-4 rounded-2 bg-white text-dark" style="font-family: Arial, sans-serif;">
          <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <div>
              <h5 class="fw-bold text-dark mb-0">PT LOGISTIK MAJU SENTOSA</h5>
              <div class="small text-secondary">Surat Bukti Pemindahan / Mutasi Stok Internal</div>
            </div>
            <div class="text-end">
              <span class="badge text-bg-info fs-6 px-3 py-2 mb-1 fw-bold text-dark">TRANSFER ORDER SLIP</span>
              <div class="fw-bold fs-5 text-dark" id="printTFNo">TF-2026-019</div>
            </div>
          </div>

          <div class="row g-2 mb-3 small">
            <div class="col-6">
              <table class="table-borderless w-100">
                <tr>
                  <td class="text-secondary" style="width: 130px;">Tanggal Transfer</td>
                  <td>: <strong id="printTFDate">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Fasilitas Asal</td>
                  <td>: <strong id="printTFOrigin">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Fasilitas Tujuan</td>
                  <td>: <strong class="text-primary" id="printTFDest">-</strong></td>
                </tr>
              </table>
            </div>
            <div class="col-6">
              <table class="table-borderless w-100">
                <tr>
                  <td class="text-secondary" style="width: 130px;">Petugas Pelaksana</td>
                  <td>: <strong id="printTFOperator">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Armada Pengangkut</td>
                  <td>: <strong id="printTFDriver">-</strong></td>
                </tr>
                <tr>
                  <td class="text-secondary">Alasan Mutasi</td>
                  <td>: <span class="fst-italic" id="printTFReason">-</span></td>
                </tr>
              </table>
            </div>
          </div>

          <table class="table table-sm table-bordered align-middle mb-4">
            <thead class="table-light text-secondary small text-uppercase">
              <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 120px;">SKU</th>
                <th>Deskripsi Barang</th>
                <th class="text-center" style="width: 90px;">Jumlah Transfer</th>
                <th class="text-center" style="width: 70px;">Satuan</th>
                <th style="width: 140px;">Rak Asal -> Rak Tujuan</th>
              </tr>
            </thead>
            <tbody id="printTFItemsBody" class="small">
            </tbody>
          </table>

          <div class="row text-center mt-4 pt-2 small">
            <div class="col-4">
              <p class="text-secondary mb-4">Gudang Pengirim,</p>
              <br>
              <div class="border-top pt-1 text-dark fw-bold" id="printTFSignSender">-</div>
            </div>
            <div class="col-4">
              <p class="text-secondary mb-4">Pengemudi / Ekspedisi,</p>
              <br>
              <div class="border-top pt-1 text-muted">( ................................ )</div>
            </div>
            <div class="col-4">
              <p class="text-secondary mb-4">Gudang Penerima,</p>
              <br>
              <div class="border-top pt-1 text-muted">( ................................ )</div>
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
  // Dataset Cache
  const doDataset = @json($outboundOrders);
  const transferDataset = @json($stockTransfers);
  let activeDOId = null;

  // Transfer item select change handler to auto-fill origin
  document.getElementById('transferSKU')?.addEventListener('change', function() {
    const val = this.value;
    if (!val) {
      document.getElementById('transferOrigin').value = '';
      return;
    }
    const parts = val.split('|');
    document.getElementById('transferOrigin').value = `${parts[3]} (${parts[4]})`;
  });

  // Filter DO Logic
  document.getElementById('doSearch')?.addEventListener('input', applyDOFilters);
  document.getElementById('doFilterStatus')?.addEventListener('change', applyDOFilters);
  document.getElementById('doFilterType')?.addEventListener('change', applyDOFilters);

  function applyDOFilters() {
    const q = document.getElementById('doSearch').value.toLowerCase().trim();
    const st = document.getElementById('doFilterStatus').value;
    const ty = document.getElementById('doFilterType').value;

    const rows = document.querySelectorAll('.do-row');
    let count = 0;

    rows.forEach(r => {
      const id = r.getAttribute('data-id').toLowerCase();
      const ref = r.getAttribute('data-ref').toLowerCase();
      const dest = r.getAttribute('data-dest').toLowerCase();
      const type = r.getAttribute('data-type');
      const status = r.getAttribute('data-status');

      const matchesQ = !q || id.includes(q) || ref.includes(q) || dest.includes(q);
      const matchesSt = !st || status === st;
      const matchesTy = !ty || type === ty;

      if (matchesQ && matchesSt && matchesTy) {
        r.style.display = '';
        count++;
      } else {
        r.style.display = 'none';
      }
    });

    document.getElementById('doCountInfo').innerText = `Menampilkan ${count} dokumen Delivery Order`;
  }

  function resetDOFilters() {
    document.getElementById('doSearch').value = '';
    document.getElementById('doFilterStatus').value = '';
    document.getElementById('doFilterType').value = '';
    applyDOFilters();
    window.showToast('Filter pengeluaran telah direset', 'info', 'Filter DO');
  }

  // Filter Transfer Logic
  document.getElementById('transferSearch')?.addEventListener('input', applyTransferFilters);
  document.getElementById('filterTransferStatus')?.addEventListener('change', applyTransferFilters);

  function applyTransferFilters() {
    const q = document.getElementById('transferSearch').value.toLowerCase().trim();
    const st = document.getElementById('filterTransferStatus').value;

    const rows = document.querySelectorAll('.transfer-row');
    let count = 0;

    rows.forEach(r => {
      const id = r.getAttribute('data-id').toLowerCase();
      const sku = r.getAttribute('data-sku').toLowerCase();
      const itm = r.getAttribute('data-item').toLowerCase();
      const from = r.getAttribute('data-from').toLowerCase();
      const to = r.getAttribute('data-to').toLowerCase();
      const status = r.getAttribute('data-status');

      const matchesQ = !q || id.includes(q) || sku.includes(q) || itm.includes(q) || from.includes(q) || to.includes(q);
      const matchesSt = !st || status === st;

      if (matchesQ && matchesSt) {
        r.style.display = '';
        count++;
      } else {
        r.style.display = 'none';
      }
    });

    document.getElementById('transferCountInfo').innerText = `Menampilkan ${count} data mutasi stok`;
  }

  function resetTransferFilters() {
    document.getElementById('transferSearch').value = '';
    document.getElementById('filterTransferStatus').value = '';
    applyTransferFilters();
    window.showToast('Filter transfer stok telah direset', 'info', 'Filter Transfer');
  }

  // Detail DO Modal
  function showDetailDO(id) {
    const data = doDataset.find(d => d.id === id);
    if (!data) return;

    activeDOId = id;
    document.getElementById('detailDOTitle').innerText = `Delivery Order: ${data.id}`;
    document.getElementById('detailDOId').innerText = data.id;
    document.getElementById('detailDODate').innerText = data.date;
    document.getElementById('detailDORef').innerText = data.reference_no;
    document.getElementById('detailDOWarehouse').innerText = data.warehouse_origin;
    document.getElementById('detailDODest').innerText = data.destination;
    document.getElementById('detailDOType').innerText = data.destination_type;
    document.getElementById('detailDODriver').innerText = data.driver;
    document.getElementById('detailDONotes').innerText = data.notes || 'Tidak ada instruksi khusus.';

    const badge = document.getElementById('detailDOStatusBadge');
    badge.className = `badge text-bg-${data.status_badge}`;
    badge.innerText = data.status_label;

    const tbody = document.getElementById('detailDOItemsBody');
    tbody.innerHTML = '';

    data.items.forEach((item, index) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="text-center">${index + 1}</td>
        <td class="font-monospace text-secondary">${item.sku}</td>
        <td class="fw-bold text-dark">${item.name}</td>
        <td class="text-center fw-bold text-warning-emphasis">${item.qty} ${item.uom}</td>
        <td><span class="badge text-bg-light border">${item.bin}</span></td>
        <td><span class="small text-secondary">${item.purpose}</span></td>
      `;
      tbody.appendChild(tr);
    });

    const modal = new bootstrap.Modal(document.getElementById('modalDetailDO'));
    modal.show();
  }

  // Print DO Slip Modal
  function showPrintDOSlip(id) {
    const data = doDataset.find(d => d.id === id);
    if (!data) return;

    document.getElementById('printDONo').innerText = data.id;
    document.getElementById('printDODate').innerText = data.date;
    document.getElementById('printDODest').innerText = data.destination;
    document.getElementById('printDOWarehouse').innerText = data.warehouse_origin;
    document.getElementById('printDORef').innerText = data.reference_no;
    document.getElementById('printDODriver').innerText = data.driver;
    document.getElementById('printDOStatus').innerText = data.status_label;
    document.getElementById('printSignDOSender').innerText = data.operator;

    const tbody = document.getElementById('printDOItemsBody');
    tbody.innerHTML = '';

    data.items.forEach((item, index) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="text-center">${index + 1}</td>
        <td class="font-monospace">${item.sku}</td>
        <td>${item.name}</td>
        <td class="text-center fw-bold">${item.qty}</td>
        <td class="text-center">${item.uom}</td>
        <td>${item.bin}</td>
        <td class="small">${item.purpose}</td>
      `;
      tbody.appendChild(tr);
    });

    const modal = new bootstrap.Modal(document.getElementById('modalPrintDOSlip'));
    modal.show();
  }

  function triggerPrintFromDetailDO() {
    if (activeDOId) {
      bootstrap.Modal.getInstance(document.getElementById('modalDetailDO'))?.hide();
      setTimeout(() => {
        showPrintDOSlip(activeDOId);
      }, 300);
    }
  }

  // Detail Transfer Modal / Slip
  function showPrintTransferSlip(id) {
    const data = transferDataset.find(t => t.id === id);
    if (!data) return;

    document.getElementById('printTFNo').innerText = data.id;
    document.getElementById('printTFDate').innerText = data.date;
    document.getElementById('printTFOrigin').innerText = `${data.from_warehouse} (${data.from_bin})`;
    document.getElementById('printTFDest').innerText = `${data.to_warehouse} (${data.to_bin})`;
    document.getElementById('printTFOperator').innerText = data.operator;
    document.getElementById('printTFDriver').innerText = data.driver;
    document.getElementById('printTFReason').innerText = data.reason;
    document.getElementById('printTFSignSender').innerText = data.operator;

    const tbody = document.getElementById('printTFItemsBody');
    tbody.innerHTML = `
      <tr>
        <td class="text-center">1</td>
        <td class="font-monospace">${data.sku}</td>
        <td><strong>${data.item_name}</strong></td>
        <td class="text-center fw-bold">${data.qty}</td>
        <td class="text-center">${data.uom}</td>
        <td>${data.from_bin} &rarr; ${data.to_bin}</td>
      </tr>
    `;

    const modal = new bootstrap.Modal(document.getElementById('modalPrintTransferSlip'));
    modal.show();
  }

  function showDetailTransfer(id) {
    showPrintTransferSlip(id);
  }

  // Stock Validation in DO Modal
  function validateDOItemStock(inputEl) {
    const row = inputEl.closest('tr');
    const select = row.querySelector('.do-item-select');
    const qtyInput = row.querySelector('.do-item-qty');
    const warningEl = row.querySelector('.stock-warning');
    const submitBtn = document.getElementById('btnSubmitDO');

    if (!select.value) return;

    const parts = select.value.split('|');
    const availableStock = parseInt(parts[3]) || 0;
    const requestedQty = parseInt(qtyInput.value) || 0;

    if (requestedQty > availableStock) {
      warningEl.classList.remove('d-none');
      warningEl.innerText = `Melebihi stok! (Tersedia: ${availableStock})`;
      qtyInput.classList.add('is-invalid');
      submitBtn.disabled = true;
    } else {
      warningEl.classList.add('d-none');
      qtyInput.classList.remove('is-invalid');
      submitBtn.disabled = false;
    }
  }

  function addDOItemRow() {
    const tbody = document.getElementById('doItemInputBody');
    const first = tbody.querySelector('tr');
    if (!first) return;

    const clone = first.cloneNode(true);
    clone.querySelector('.do-item-select').value = '';
    clone.querySelector('.do-item-qty').value = '1';
    clone.querySelector('.do-item-purpose').value = 'Alokasi Pesanan';
    clone.querySelector('.stock-warning').classList.add('d-none');
    clone.querySelector('.do-item-qty').classList.remove('is-invalid');
    tbody.appendChild(clone);
  }

  function removeDOItemRow(btn) {
    const rows = document.querySelectorAll('#doItemInputBody tr');
    if (rows.length > 1) {
      btn.closest('tr').remove();
      document.getElementById('btnSubmitDO').disabled = false;
    } else {
      window.showToast('Minimal harus ada 1 item barang yang dikeluarkan!', 'warning', 'Peringatan');
    }
  }

  // Handle Form DO Submission
  function handleDOSubmit(event) {
    event.preventDefault();

    const ref = document.getElementById('doRefNo').value.trim();
    const type = document.getElementById('doType').value;
    const dest = document.getElementById('doDestination').value.trim();
    const warehouse = document.getElementById('doWarehouse').value;
    const driver = document.getElementById('doDriver').value.trim();
    const status = document.getElementById('doInitialStatus').value;
    const notes = document.getElementById('doNotes').value.trim();

    const rows = document.querySelectorAll('#doItemInputBody tr');
    const items = [];
    let totalQty = 0;

    rows.forEach(r => {
      const sel = r.querySelector('.do-item-select').value;
      if (!sel) return;
      const parts = sel.split('|');
      const qty = parseInt(r.querySelector('.do-item-qty').value) || 0;
      const pur = r.querySelector('.do-item-purpose').value.trim();

      totalQty += qty;
      items.push({
        sku: parts[0],
        name: parts[1],
        qty: qty,
        uom: parts[2] || 'Unit',
        bin: parts[4] || 'Rak B2-01',
        purpose: pur || 'Pengeluaran Order'
      });
    });

    if (items.length === 0) {
      window.showToast('Pilih minimal 1 SKU barang!', 'danger', 'Validasi Gagal');
      return;
    }

    const nextId = `DO-2026-${String(doDataset.length + 85).padStart(3, '0')}`;
    const now = new Date();
    const dateStr = now.toISOString().slice(0, 10) + ' ' + now.toTimeString().slice(0, 5);

    const newDO = {
      id: nextId,
      reference_no: ref,
      date: dateStr,
      destination: dest,
      destination_type: type,
      warehouse_origin: warehouse,
      operator: '{{ $currentRole["user_name"] ?? "Joko Prasetyo" }}',
      driver: driver,
      status: status,
      status_label: status === 'DELIVERED' ? 'Terkirim & Selesai' : (status === 'SHIPPED' ? 'Dalam Perjalanan (In Transit)' : 'Sedang Dikemas (Packing)'),
      status_badge: status === 'DELIVERED' ? 'success' : (status === 'SHIPPED' ? 'info' : 'warning'),
      notes: notes,
      total_qty: totalQty,
      items: items
    };

    doDataset.unshift(newDO);

    const tbody = document.querySelector('#doTable tbody');
    const tr = document.createElement('tr');
    tr.className = 'do-row bg-warning-subtle';
    tr.setAttribute('data-id', newDO.id);
    tr.setAttribute('data-ref', newDO.reference_no);
    tr.setAttribute('data-dest', newDO.destination);
    tr.setAttribute('data-type', newDO.destination_type);
    tr.setAttribute('data-status', newDO.status);

    tr.innerHTML = `
      <td class="ps-3">
        <span class="fw-bold text-dark d-block">${newDO.id} <span class="badge text-bg-warning ms-1 text-dark">Baru</span></span>
        <span class="text-secondary small"><i class="bi bi-clock me-1"></i>${newDO.date}</span>
      </td>
      <td>
        <span class="badge text-bg-light border text-dark">
          <i class="bi bi-file-earmark-check text-warning me-1"></i>${newDO.reference_no}
        </span>
      </td>
      <td>
        <span class="fw-semibold text-dark d-block">${newDO.destination}</span>
        <span class="badge text-bg-secondary-subtle text-secondary small border">${newDO.destination_type}</span>
      </td>
      <td>
        <span class="text-dark d-block">${newDO.warehouse_origin}</span>
        <span class="text-secondary small">Staging Bay Keluar</span>
      </td>
      <td class="text-center">
        <span class="fw-bold text-warning-emphasis fs-6">${newDO.items.length} SKU</span>
        <div class="text-secondary small">${newDO.total_qty} unit</div>
      </td>
      <td>
        <div class="text-dark fw-medium">${newDO.operator}</div>
        <div class="text-secondary small"><i class="bi bi-truck me-1"></i>${newDO.driver}</div>
      </td>
      <td class="text-center">
        <span class="badge text-bg-${newDO.status_badge} rounded-pill px-2 py-1">
          ${newDO.status_label}
        </span>
      </td>
      <td class="text-end pe-3">
        <div class="btn-group btn-group-sm">
          <button type="button" class="btn btn-outline-info" title="Lihat Rincian DO" onclick="showDetailDO('${newDO.id}')">
            <i class="bi bi-eye"></i>
          </button>
          <button type="button" class="btn btn-outline-secondary" title="Cetak Surat Jalan (DO)" onclick="showPrintDOSlip('${newDO.id}')">
            <i class="bi bi-printer"></i>
          </button>
        </div>
      </td>
    `;

    tbody.insertBefore(tr, tbody.firstChild);

    bootstrap.Modal.getInstance(document.getElementById('modalAddDO'))?.hide();
    document.getElementById('formAddDO').reset();
    applyDOFilters();

    window.showToast(`Surat Jalan ${newDO.id} berhasil diterbitkan dengan status "${newDO.status_label}"!`, 'success', 'DO Diterbitkan');
  }

  // Quick Approve DO Action
  function quickApproveDO(id, btn) {
    const data = doDataset.find(d => d.id === id);
    if (!data) return;

    data.status = 'SHIPPED';
    data.status_label = 'Dalam Perjalanan (In Transit)';
    data.status_badge = 'info';

    const row = btn.closest('tr');
    row.setAttribute('data-status', 'SHIPPED');
    const badge = row.querySelector('td:nth-child(7) span');
    badge.className = 'badge text-bg-info rounded-pill px-2 py-1';
    badge.innerText = data.status_label;
    btn.remove();

    window.showToast(`Delivery Order ${id} telah disetujui & beralih ke status SHIPPED!`, 'success', 'Approval Berhasil');
  }

  // Handle Form Transfer Submission
  function handleTransferSubmit(event) {
    event.preventDefault();

    const skuVal = document.getElementById('transferSKU').value;
    if (!skuVal) {
      window.showToast('Pilih SKU barang yang akan dimutasi!', 'danger', 'Validasi Gagal');
      return;
    }

    const parts = skuVal.split('|');
    const sku = parts[0];
    const itemName = parts[1];
    const uom = parts[2];
    const originWarehouse = parts[3];
    const originBin = parts[4];
    const currentStock = parseInt(parts[5]) || 0;

    const destWarehouse = document.getElementById('destWarehouse')?.value || document.getElementById('transferDestWarehouse').value;
    const destBin = document.getElementById('transferDestBin').value.trim();
    const qty = parseInt(document.getElementById('transferQty').value) || 0;
    const vehicle = document.getElementById('transferVehicle').value.trim();
    const operator = document.getElementById('transferOperator').value;
    const reason = document.getElementById('transferReason').value.trim() || 'Pemindahan buffer stock antar gudang';

    if (qty > currentStock) {
      window.showToast(`Kuantitas transfer (${qty}) melebihi stok yang ada di ${originWarehouse} (${currentStock})!`, 'danger', 'Stok Tidak Cukup');
      return;
    }

    const nextId = `TF-2026-${String(transferDataset.length + 22).padStart(3, '0')}`;
    const now = new Date();
    const dateStr = now.toISOString().slice(0, 10) + ' ' + now.toTimeString().slice(0, 5);

    const newTransfer = {
      id: nextId,
      date: dateStr,
      from_warehouse: originWarehouse,
      from_bin: originBin,
      to_warehouse: destWarehouse,
      to_bin: destBin,
      sku: sku,
      item_name: itemName,
      qty: qty,
      uom: uom,
      operator: operator,
      driver: vehicle,
      reason: reason,
      status: 'IN_TRANSIT',
      status_label: 'Dalam Perjalanan Antar Gudang',
      status_badge: 'info'
    };

    transferDataset.unshift(newTransfer);

    const tbody = document.querySelector('#transferTable tbody');
    const tr = document.createElement('tr');
    tr.className = 'transfer-row bg-info-subtle';
    tr.setAttribute('data-id', newTransfer.id);
    tr.setAttribute('data-sku', newTransfer.sku);
    tr.setAttribute('data-item', newTransfer.item_name);
    tr.setAttribute('data-from', newTransfer.from_warehouse);
    tr.setAttribute('data-to', newTransfer.to_warehouse);
    tr.setAttribute('data-status', newTransfer.status);

    tr.innerHTML = `
      <td class="ps-3">
        <span class="fw-bold text-dark d-block">${newTransfer.id} <span class="badge text-bg-info ms-1 text-dark">Baru</span></span>
        <span class="text-secondary small"><i class="bi bi-clock me-1"></i>${newTransfer.date}</span>
      </td>
      <td>
        <div class="d-flex align-items-center gap-1">
          <div>
            <strong class="text-dark small d-block">${newTransfer.from_warehouse}</strong>
            <span class="badge text-bg-light border text-secondary small">${newTransfer.from_bin}</span>
          </div>
          <i class="bi bi-arrow-right text-primary mx-1"></i>
          <div>
            <strong class="text-primary small d-block">${newTransfer.to_warehouse}</strong>
            <span class="badge text-bg-info-subtle text-info border small">${newTransfer.to_bin}</span>
          </div>
        </div>
      </td>
      <td>
        <span class="fw-semibold text-dark d-block">${newTransfer.item_name}</span>
        <span class="font-monospace text-secondary small">${newTransfer.sku}</span>
      </td>
      <td class="text-center">
        <span class="fw-bold fs-6 text-dark">${newTransfer.qty}</span>
        <span class="text-secondary small d-block">${newTransfer.uom}</span>
      </td>
      <td>
        <div class="text-dark fw-medium">${newTransfer.operator}</div>
        <div class="text-secondary small"><i class="bi bi-truck me-1"></i>${newTransfer.driver}</div>
      </td>
      <td>
        <span class="text-secondary small fst-italic">${newTransfer.reason}</span>
      </td>
      <td class="text-center">
        <span class="badge text-bg-${newTransfer.status_badge} rounded-pill px-2 py-1">
          ${newTransfer.status_label}
        </span>
      </td>
      <td class="text-end pe-3">
        <div class="btn-group btn-group-sm">
          <button type="button" class="btn btn-outline-info" title="Lihat Rincian Mutasi" onclick="showDetailTransfer('${newTransfer.id}')">
            <i class="bi bi-eye"></i>
          </button>
          <button type="button" class="btn btn-outline-secondary" title="Cetak Surat Mutasi" onclick="showPrintTransferSlip('${newTransfer.id}')">
            <i class="bi bi-printer"></i>
          </button>
        </div>
      </td>
    `;

    tbody.insertBefore(tr, tbody.firstChild);

    bootstrap.Modal.getInstance(document.getElementById('modalAddTransfer'))?.hide();
    document.getElementById('formAddTransfer').reset();
    document.getElementById('transferOrigin').value = '';
    applyTransferFilters();

    // Switch to transfer tab to view
    const triggerEl = document.querySelector('#transfer-tab');
    bootstrap.Tab.getInstance(triggerEl)?.show() || new bootstrap.Tab(triggerEl).show();

    window.showToast(`Mutasi ${newTransfer.id} dari ${newTransfer.from_warehouse} ke ${newTransfer.to_warehouse} berhasil diproses!`, 'success', 'Mutasi Berhasil');
  }

  // Quick Approve Transfer
  function quickApproveTransfer(id, btn) {
    const data = transferDataset.find(t => t.id === id);
    if (!data) return;

    data.status = 'COMPLETED';
    data.status_label = 'Selesai & Diterima di Tujuan';
    data.status_badge = 'success';

    const row = btn.closest('tr');
    row.setAttribute('data-status', 'COMPLETED');
    const badge = row.querySelector('td:nth-child(7) span');
    badge.className = 'badge text-bg-success rounded-pill px-2 py-1';
    badge.innerText = data.status_label;
    btn.remove();

    window.showToast(`Mutasi ${id} telah diverifikasi dan stok telah diterima di fasilitas tujuan!`, 'success', 'Mutasi Selesai');
  }

  function exportOutboundData() {
    window.showToast('Mengekspor rekapitulasi data pengeluaran (DO) dan mutasi stok ke Excel...', 'info', 'Ekspor Outbound');
  }
</script>
@endpush
