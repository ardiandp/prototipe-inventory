# PRD — Inventory & Warehouse Management System (Prototype)

**Versi:** 1.0  
**Jenis Produk:** High-Fidelity Interactive UI Prototype  
**Framework:** Laravel 13 (PHP 8.3)  
**UI Engine:** AdminLTE 4 (Bootstrap 5.3)  
**Status:** Spesifikasi Acuan Implementasi Prototipe  

---

## 1. Ringkasan Produk

Prototipe **Inventory & Warehouse Management System** adalah aplikasi manajemen persediaan dan pergudangan berbasis web yang dirancang untuk mendemonstrasikan alur operasional pengelolaan stok barang secara komprehensif, interaktif, dan modern. 

Prototipe ini ditujukan untuk presentasi demo kepada calon klien/stakeholder guna memvalidasi kebutuhan bisnis dan alur kerja (*user journey*) sebelum pengembangan backend skala penuh dimulai.

> [!IMPORTANT]
> **Fokus Utama: Tampilan & Simulasi Interaksi.**  
> Seluruh alur data menggunakan data tiruan (*mock data*) yang tersinkronisasi di sisi client (JavaScript / Blade session). **Tidak ada database fisik, migration tabel bisnis, atau API eksternal** yang dibuat atau diakses selama pengembangan fase prototipe ini.

---

## 2. Peran Pengguna (Roles & Permissions)

Prototipe menyediakan fitur **Role Switcher** interaktif di topbar aplikasi untuk menguji pengalaman pengguna dari berbagai sudut pandang:

| Role | Kode | Fokus Akses & Tanggung Jawab |
|---|---|---|
| **Warehouse Manager** | `wh_manager` | Akses penuh seluruh modul gudang, persetujuan penyesuaian stok (*stock adjustment*), laporan analitik, dan kontrol operasional. |
| **Warehouse Staff** | `wh_staff` | Operasional harian: input penerimaan barang (*inbound*), pengeluaran barang (*outbound*), mutasi antar rak, dan input opname fisik. |
| **Purchasing / Buyer** | `purchasing` | Pemantauan stok menipis (*reorder point alert*), master supplier, dan riwayat penerimaan barang dari pembelian. |
| **Finance / Auditor** | `finance` | Audit nilai persediaan, laporan valuasi stok (*stock valuation*), riwayat opname, dan kartu stok. |
| **Super Admin** | `admin` | Pengaturan master lokasi gudang, kategori, user role switcher, dan konfigurasi sistem. |

---

## 3. Fitur & Modul Utama

### 3.1 App Shell & Desain (AdminLTE 4)
- **Tema:** AdminLTE 4 berbasis Bootstrap 5.3 dengan mode Terang/Gelap (*Color Mode Toggle*).
- **Topbar:** Brand logo, tombol toggle sidebar (*push menu*), indikator alert stok, role switcher interaktif, dan profil user.
- **Sidebar:** Navigasi modular terkelompok (Dashboard, Master Data, Operasional Stok, Audit & Opname, Laporan).
- **Feedback:** Notifikasi toast animasi dan modal dialog interaktif untuk setiap simulasi aksi (simpan, tolak, setujui).

### 3.2 Dashboard Inventaris
- **Ringkasan KPI (Stat Cards):**
  - Total SKU Aktif
  - Total Nilai Aset Stok (Rp)
  - Peringatan Stok Menipis (*Low Stock Alert*)
  - Total Mutasi Masuk & Keluar Bulan Berjalan
- **Grafik Tren Persediaan:** Grafik interaktif pergerakan barang masuk vs keluar mingguan.
- **Distribusi Kategori:** Grafik donat sebaran item per kategori/gudang.
- **Quick Action & Pending Tasks:** Antrean persetujuan mutasi stok dan daftar item yang mendesak untuk di-reorder.

### 3.3 Master Data Persediaan
- **Master Barang (Items/SKU):**
  - Kolom: SKU, Nama Barang, Kategori, Satuan/UOM (Pcs, Box, Kg), Min Stock, Max Stock, Harga Beli, Harga Jual, Status Rak/Gudang.
  - Fitur: Live filter kategori, status stok (Aman, Menipis, Kosong), modal detail barang, modal form tambah/edit simulasi.
- **Master Kategori & Lokasi Gudang:**
  - Gudang Utama, Gudang Transit, Gudang Retur, serta zonasi Rak/Bin.
- **Master Vendor/Supplier:**
  - Data pemasok resmi, kontak, dan alamat.

### 3.4 Operasional Inbound (Penerimaan Barang)
- Daftar Goods Receipt (GR) dengan status (*Received, Inspected, Draft*).
- Form interaktif penerimaan barang dengan pemilihan supplier, nomor surat jalan, dan rincian item.
- Modal preview cetak Surat Bukti Penerimaan Barang (*mock print layout*).

### 3.5 Operasional Outbound & Transfer (Pengeluaran & Mutasi)
- Pengeluaran barang untuk kebutuhan produksi / penjualan dengan nomor referensi DO dummy.
- Mutasi stok antar gudang atau pemindahan rak dengan alur validasi pengirim & penerima.
- Status pelacakan: *Pending Approval, In-Transit, Completed*.

### 3.6 Stock Opname & Penyesuaian (Stock Adjustment)
- Riwayat sesi Stock Opname fisik berkala.
- Form pencatatan selisih hitung fisik vs saldo sistem.
- Alur persetujuan selisih stok (*write-off* surplus atau minus) dengan alasan penyesuaian (rusak, susut, selisih hitung).

### 3.7 Kartu Stok & Laporan Mutasi (Analytics)
- **Kartu Stok Digital:** Riwayat kronologis detail mutasi per SKU (Tanggal, No. Dokumen, Jenis Transaksi, Masuk, Keluar, Saldo Akhir).
- **Laporan Komprehensif:** Filter periode tanggal, kategori, dan gudang.
- **Simulasi Ekspor:** Tombol ekspor format Excel/PDF simulasi dengan feedback toast.

---

## 4. Arsitektur Teknis Prototipe

- **Backend:** Laravel 13 Framework (PHP 8.3 CLI).
- **Frontend Template:** AdminLTE v4 (terletak di `public/theme/AdminLTE-4`), mengandalkan CSS/JS dist Bootstrap 5.
- **State & Data Store:** File terpusat `resources/js/mock-inventory.js` atau helper mock data terstruktur di service class mock, menyediakan dataset ~30 SKU barang realistis, 5 kategori, 3 gudang, dan transaksi mutasi historis.
- **Routing:** Seluruh antarmuka diakses melalui web routing Laravel yang memetakan URL bersih (`/dashboard`, `/inventory/items`, `/inventory/inbound`, `/inventory/outbound`, `/inventory/opname`, `/inventory/reports`).

---

## 5. Rencana Fase & Roadmap Task

- **T0.1:** Audit repository existing & baseline setup *(Selesai)*.
- **T0.2:** Penyusunan spesifikasi PRD & Implementation Plan prototipe *(Task Berjalan)*.
- **T0.3:** Penataan struktur workspace dan integrasi link aset AdminLTE-4.
- **T1:** Implementasi App Shell AdminLTE-4 (Topbar, Sidebar, Breadcrumb, Dark/Light Mode).
- **T2:** Implementasi Role & Permission Engine (Role switcher, badge otorisasi).
- **T3:** Dataset Mock Terpusat (Data SKU, master lokasi, supplier, dan riwayat mutasi).
- **T4:** Dashboard Inventaris Lengkap (KPI cards, chart tren mutasi, alert stok).
- **T5:** Modul Master Data Barang & Kategori (Tabel interaktif, filter, modal detail).
- **T6:** Modul Master Data Gudang & Supplier.
- **T7:** Modul Penerimaan Barang (Inbound Goods Receipt).
- **T8:** Modul Pengeluaran & Transfer Stok (Outbound & Stock Transfer).
- **T9:** Modul Stock Opname & Penyesuaian Selisih.
- **T10:** Modul Kartu Stok Digital & Pusat Laporan.
- **T11:** Cross-Role Workflow QA, Verifikasi Semua Route, & Demo Readiness.
