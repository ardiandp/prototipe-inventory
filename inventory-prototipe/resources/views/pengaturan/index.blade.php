@extends('layouts.app')

@section('title', 'Pengaturan & Konfigurasi Prototipe')

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
      <h3 class="mb-1 fw-bold text-dark">
        <i class="bi bi-sliders text-secondary me-2"></i>Pengaturan Prototipe & Panduan Demo
      </h3>
      <p class="text-secondary mb-0 small">
        Konfigurasi lingkungan simulasi, matriks otorisasi 5 peran pengguna, panduan alur end-to-end, dan pemulihan data mock.
      </p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-danger btn-sm" onclick="resetPrototypeData()">
        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Data Mock ke Awal
      </button>
      <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-speedometer2 me-1"></i>Kembali ke Dashboard
      </a>
    </div>
  </div>

  <div class="row g-4">
    <!-- Kolom Kiri: Spesifikasi Teknis & Panduan Skenario Demo -->
    <div class="col-12 col-lg-7">
      <!-- Spesifikasi Prototipe -->
      <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3">
          <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-cpu text-primary me-2"></i>Spesifikasi Lingkungan Prototipe
          </h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="p-3 bg-light rounded-2">
                <span class="text-secondary small d-block">Framework & Runtime</span>
                <strong class="text-dark">Laravel 13.42.0 (PHP 8.3.28)</strong>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="p-3 bg-light rounded-2">
                <span class="text-secondary small d-block">Desain & Tema UI</span>
                <strong class="text-dark">AdminLTE 4 (Bootstrap 5.3)</strong>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="p-3 bg-light rounded-2">
                <span class="text-secondary small d-block">Data Store Architecture</span>
                <strong class="text-dark">Centralized Mock Store (No Physical DB)</strong>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="p-3 bg-light rounded-2">
                <span class="text-secondary small d-block">Total Katalog Aktif</span>
                <strong class="text-dark">32 SKU Barang di 3 Gudang Logistik</strong>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Skenario Demo Interaktif -->
      <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3">
          <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-diagram-3 text-success me-2"></i>Panduan Skenario Alur Demonstrasi Stakeholder
          </h5>
        </div>
        <div class="card-body">
          <div class="list-group list-group-flush">
            <div class="list-group-item px-0 py-3">
              <div class="d-flex align-items-start">
                <span class="badge text-bg-primary rounded-circle p-2 me-3">1</span>
                <div>
                  <h6 class="fw-bold text-dark mb-1">Switching Peran Otorisasi (5 Roles)</h6>
                  <p class="text-secondary small mb-1">
                    Gunakan dropdown <strong>Role Switcher</strong> di pojok kanan atas topbar untuk berganti peran antara <em>Warehouse Manager</em>, <em>Warehouse Staff</em>, <em>Purchasing</em>, <em>Finance</em>, dan <em>Super Admin</em>. Amati adaptasi menu sidebar dan tombol operasional.
                  </p>
                </div>
              </div>
            </div>
            <div class="list-group-item px-0 py-3">
              <div class="d-flex align-items-start">
                <span class="badge text-bg-info rounded-circle p-2 me-3">2</span>
                <div>
                  <h6 class="fw-bold text-dark mb-1">Penerimaan Barang Masuk (Inbound Goods Receipt)</h6>
                  <p class="text-secondary small mb-1">
                    Buka modul <code>/operasional/inbound</code>, klik <strong>+ Catat Penerimaan Baru</strong>, isi nomor surat jalan vendor dan pilih SKU barang. Setelah submit, baris baru akan muncul di tabel dan Anda dapat langsung mengklik tombol <strong>Cetak Surat Penerimaan</strong>.
                  </p>
                </div>
              </div>
            </div>
            <div class="list-group-item px-0 py-3">
              <div class="d-flex align-items-start">
                <span class="badge text-bg-warning rounded-circle p-2 me-3">3</span>
                <div>
                  <h6 class="fw-bold text-dark mb-1">Pengeluaran & Mutasi Transfer Antar Gudang (Outbound)</h6>
                  <p class="text-secondary small mb-1">
                    Buka modul <code>/operasional/outbound</code>. Uji fitur <strong>Validasi Batas Stok Realtime</strong> saat input DO. Uji pula tab <strong>Transfer Stok</strong> untuk memindahkan barang dari Gudang Utama ke Transit Karawang dan lakukan <em>Quick Approve</em>.
                  </p>
                </div>
              </div>
            </div>
            <div class="list-group-item px-0 py-3">
              <div class="d-flex align-items-start">
                <span class="badge text-bg-success rounded-circle p-2 me-3">4</span>
                <div>
                  <h6 class="fw-bold text-dark mb-1">Stock Opname & Live Variance Calculator</h6>
                  <p class="text-secondary small mb-1">
                    Buka modul <code>/operasional/opname</code>, klik <strong>Input Hitung Fisik</strong>. Ketikkan angka fisik yang berbeda dari saldo sistem untuk melihat <em>Live Variance Calculator</em> menghitung selisih dan status secara realtime. Sesi selisih dapat disetujui melalui tombol <em>Approve Adjustment</em>.
                  </p>
                </div>
              </div>
            </div>
            <div class="list-group-item px-0 py-3">
              <div class="d-flex align-items-start">
                <span class="badge text-bg-purple rounded-circle p-2 me-3 text-bg-secondary">5</span>
                <div>
                  <h6 class="fw-bold text-dark mb-1">Kartu Stok Perpetual & Rekapitulasi Valuasi Aset</h6>
                  <p class="text-secondary small mb-1">
                    Buka <code>/laporan/kartu-stok</code> untuk menginspeksi buku besar saldo berjalan per SKU, lalu lanjutkan ke <code>/laporan/mutasi</code> untuk melihat valuasi persediaan Rp 337.696.500 dengan total rekapitulasi mutasi dinamis.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Matriks Otorisasi 5 Peran Pengguna -->
    <div class="col-12 col-lg-5">
      <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3">
          <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-shield-lock text-info me-2"></i>Matriks Otorisasi 5 Peran Pengguna
          </h5>
        </div>
        <div class="card-body p-0">
          <div class="list-group list-group-flush small">
            @foreach($roles as $key => $r)
              <div class="list-group-item p-3 {{ ($currentRole['id'] ?? '') === $r['id'] ? 'bg-light border-start border-3 border-primary' : '' }}">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge text-bg-{{ $r['badge'] }} px-2 py-1">{{ $r['name'] }}</span>
                    <strong class="text-dark">{{ $r['user_name'] }}</strong>
                  </div>
                  @if(($currentRole['id'] ?? '') === $r['id'])
                    <span class="badge text-bg-primary">Aktif</span>
                  @else
                    <a href="{{ url('/switch-role/' . $key) }}" class="btn btn-xs btn-outline-secondary">Ganti ke Peran Ini</a>
                  @endif
                </div>
                <p class="text-secondary mb-2">{{ $r['description'] }}</p>
                <div class="d-flex flex-wrap gap-1">
                  @foreach($r['permissions'] as $p)
                    <span class="badge bg-secondary-subtle text-secondary border font-monospace" style="font-size: 0.7rem;">{{ $p }}</span>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Quick Reset Card -->
      <div class="card border-0 shadow-sm rounded-3 bg-light-subtle border">
        <div class="card-body p-3">
          <h6 class="fw-bold text-dark mb-1"><i class="bi bi-arrow-repeat text-primary me-1"></i>Simulasi Penyegaran Prototipe</h6>
          <p class="small text-secondary mb-3">
            Bila Anda telah menambahkan banyak data dummy saat demonstrasi dan ingin mengembalikan dataset ke kondisi awal, klik tombol di bawah ini:
          </p>
          <button class="btn btn-sm btn-outline-secondary w-100" onclick="resetPrototypeData()">
            <i class="bi bi-arrow-clockwise me-1"></i>Segarkan Kembali Dataset Awal
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function resetPrototypeData() {
    window.showToast('Data prototipe telah diinisialisasi ulang ke dataset standar!', 'success', 'Reset Berhasil');
    setTimeout(() => {
      window.location.reload();
    }, 800);
  }
</script>
@endpush
