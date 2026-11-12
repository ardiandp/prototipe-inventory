# Inventory & Warehouse Management System Prototype (AdminLTE-4 & Laravel 13)

Prototipe interaktif *high-fidelity* untuk **Sistem Manajemen Inventaris & Pergudangan Terpadu (Warehouse Management System / WMS)** berbasis **Laravel 13** dan antarmuka modern **AdminLTE 4 (Bootstrap 5.3)**.

Prototipe ini dirancang khusus untuk demonstrasi kebutuhan bisnis, validasi alur operasional multi-peran, dan pengujian pengalaman pengguna (*UI/UX*) tanpa ketergantungan pada database fisik. Seluruh transaksi dan saldo inventaris didukung oleh arsitektur *Centralized Mock Store* yang konsisten.

---

## 🌟 Fitur Utama & Modul Sistem

### 1. Multi-Role & Permission Engine (5 Peran Pengguna)
Dilengkapi dengan fitur **Topbar Role Switcher** interaktif untuk berpindah peran secara instan:
- **Warehouse Manager (`Budi Santoso`)**: Akses penuh ke seluruh modul, persetujuan mutasi, dan laporan analitik.
- **Warehouse Staff (`Joko Prasetyo`)**: Operasional harian: pencatatan penerimaan (GR), pengeluaran (DO), transfer, dan hitung fisik opname.
- **Purchasing / Buyer (`Siti Rahma`)**: Pemantauan stok menipis, pengadaan ulang (*quick reorder*), direktori vendor, dan riwayat penerimaan.
- **Finance / Auditor (`Hendra Wijaya`)**: Audit persediaan fisik, evaluasi selisih (*variance analysis*), persetujuan *write-off*, dan valuasi aset persediaan.
- **Super Admin (`Bambang Sugiarto`)**: Pengawasan sistem menyeluruh, konfigurasi data master, dan panduan demonstrasi.

### 2. Dashboard Eksekutif & Operasional (`/dashboard`)
- **4 Kartu KPI Utama**: Total SKU (32 Item), Total Valuasi Aset (Rp 337.696.500), Peringatan Stok Kritis (7 Alert), dan Pergerakan Bulanan.
- **Grafik Tren Mutasi Mingguan**: Perbandingan visual tren barang masuk (*Inbound*) vs barang keluar (*Outbound*) berbasis Chart.js.
- **Grafik Donat Kategori**: Sebaran komposisi persediaan (*Bahan Baku*, *Elektronik*, *Packaging*, *Safety Gear*, *Finish Goods*).
- **Tabel Peringatan Stok Kritis**: Notifikasi otomatis item menipis dan tombol *Quick Reorder* interaktif.
- **Visualisasi Kapasitas Multi-Gudang**: Persentase keterisian 3 fasilitas gudang logistik.

### 3. Master Data Terpadu
- **Katalog Barang & SKU (`/master/barang`)**: Pencarian *live search* instan, filter multi-dimensi per kategori & status stok, modal rincian spesifikasi teknis, serta formulir tambah/edit barang.
- **Fasilitas Gudang & Zonasi Rak (`/master/lokasi`)**: Pemetaan 3 fasilitas (*Gudang Utama Cikarang*, *Gudang Transit Karawang*, *Gudang Retur QC*), visualisasi kapasitas terpakai, dan modal rincian rak/bin.
- **Direktori Vendor & Supplier (`/master/supplier`)**: Direktori 5 pemasok resmi, rating kepatuhan, nomor kontak, dan modal profil vendor.

### 4. Operasional Pergudangan
- **Penerimaan Barang / Inbound (`/operasional/inbound`)**: Pencatatan kedatangan armada vendor, pemeriksaan surat jalan (DO), antrian inspeksi QC, penempatan ke rak staging, dan modal cetak **Surat Bukti Penerimaan Barang (Goods Receipt Slip)** siap cetak (`window.print()`).
- **Pengeluaran & Transfer Stok / Outbound (`/operasional/outbound`)**:
  - *Delivery Order (DO)*: Pengeluaran barang ke divisi produksi atau pelanggan eksternal dengan **validasi batas ketersediaan stok realtime**.
  - *Transfer Antar Gudang*: Pemindahan stok multi-gudang (Gudang Utama &rarr; Transit Karawang &rarr; Retur) lengkap dengan alur persetujuan (*quick approve*).
  - Modal cetak **Surat Jalan (DO)** dan **Transfer Order Slip**.
- **Stock Opname & Penyesuaian Selisih (`/operasional/opname`)**:
  - Penjadwalan audit fisik persediaan (*cycle count* atau *wall-to-wall*).
  - Formulir *Stock Count Sheet* dengan **Kalkulator Selisih Realtime (Live Variance Calculator)** untuk menghitung selisih kuantitas dan dampak nilai rupiah secara langsung.
  - Alur persetujuan selisih (*Stock Adjustment / Write-off Approval*).
  - Modal cetak **Berita Acara Hasil Stock Opname (BAST)** resmi 4 tanda tangan.

### 5. Buku Besar & Laporan Analitik
- **Kartu Stok Digital (`/laporan/kartu-stok`)**: Buku besar kronologis keluar-masuk barang per SKU dengan perhitungan saldo berjalan (*perpetual running balance*) dan pemilih SKU dinamis.
- **Laporan Mutasi & Valuasi Persediaan (`/laporan/mutasi`)**: Rekapitulasi pergerakan kuantitas 32 SKU (*Saldo Awal*, *Inbound*, *Outbound*, *Adjustment*, *Saldo Akhir*) dan akumulasi nilai valuasi persediaan dinamis.
- **Pengaturan & Panduan Demo (`/pengaturan`)**: Matriks otorisasi peran, panduan skenario demonstrasi 5 langkah, dan tombol reset dataset mock.

---

## 🛠️ Arsitektur & Tumpukan Teknologi

- **Backend:** Laravel 13.42.0 (PHP 8.3.28)
- **Frontend / UI Theme:** AdminLTE 4 (Bootstrap 5.3, Bootstrap Icons, Chart.js)
- **Data Engine:** Centralized In-Memory Mock Store (`App\Support\MockDataStore`)
- **Role Engine:** RBAC Permission Helper (`App\Support\RoleManager`)
- **Notification:** Floating Toast Container (`window.showToast()`)
- **Print Utility:** CSS Print Stylesheets & Modal Print Preview

---

## 🚀 Panduan Menjalankan Aplikasi

### Opsi 1: Menjalankan via Laragon (Rekomendasi)
Workspace telah dilengkapi dengan file `index.php` pada *root* direktori yang secara otomatis mem-forward request Laragon ke subfolder `inventory-prototipe/public`.
1. Letakkan folder proyek di `C:\laragon\www\PORTO\inventory`.
2. Buka browser dan akses URL:
   ```
   http://localhost/PORTO/inventory/
   ```
   Aplikasi akan otomatis mengarahkan ke dashboard utama.

### Opsi 2: Menjalankan via PHP Artisan Serve
Jalankan perintah berikut melalui terminal Laragon atau PowerShell:
```powershell
cd c:\laragon\www\PORTO\inventory\inventory-prototipe
& "C:\laragon\bin\php\8.3.28\php.exe" artisan serve
```
Akses melalui browser: `http://localhost:8000/dashboard`

---

## 🧪 Hasil Verifikasi & Testing (100% Lolos)

Seluruh modul dan endpoint telah diverifikasi menggunakan pengujian terotomasi:
- **13 Route HTTP:** 100% menghasilkan status `HTTP 200 OK`
- **Role Switcher:** 100% menghasilkan `HTTP 302 Redirect` dan memperbarui konteks pengguna
- **Komponen UI:** Seluruh modal, tabel interaktif, live calculator, dan dokumen cetak ter-render tanpa broken asset.

---

## 📄 Lisensi
Prototipe ini dikembangkan untuk kebutuhan portofolio dan demonstrasi sistem inventaris logistik modern. Lisensi di bawah [MIT License](LICENSE).

