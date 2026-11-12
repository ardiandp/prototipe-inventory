# IMPLEMENTATION_PLAN.md — Inventory & Warehouse Management System Prototype v1.0

## 1. Prinsip Utama

1. **Satu Task Per Giliran:** Kerjakan satu task pada satu waktu secara berurutan sesuai roadmap.
2. **Acceptance Gate Wajib:** Setiap task harus memenuhi seluruh acceptance criteria, dites (routing/rendering/syntax), dicatat di `PROGRESS.md`, dan di-commit satu per satu sebelum melangkah ke task berikutnya.
3. **Tanpa Database / Migration:** Tidak ada pembuatan koneksi database fisik, migration, atau tabel bisnis. Seluruh data disimulasikan menggunakan mock store terpusat.
4. **UI Foundation:** Menggunakan template AdminLTE 4 yang tersedia di `public/theme/AdminLTE-4` (Bootstrap 5.3).
5. **Simulasi Interaktif Penuh:** Form, filter, pagination mock, modal, approval alur mutasi, dan feedback notifikasi toast harus dapat diuji secara interaktif pada browser.

---

## 2. Roadmap Task & Acceptance Criteria

| Task ID | Nama Task | Fokus & Deliverables | Acceptance Gate |
|:---|:---|:---|:---|
| **T0.1** | Audit Repository Existing | Audit PHP 8.3, Laravel 13, dependensi, git init, dan inventarisasi aset tema. | Laporan audit lengkap, git repo initialized, commit baseline hash. *(Selesai)* |
| **T0.2** | Spesifikasi Prototipe | Pembuatan `docs/PRD_PROTOTYPE.md` dan `IMPLEMENTATION_PLAN.md`. | PRD dan Plan terdokumentasi terstruktur, disetujui, dan di-commit. |
| **T0.3** | Penataan Workspace & Aset Tema | Standardisasi path aset AdminLTE-4 dan konfigurasi routing root agar ramah vhost Laragon. | Route `/` merender halaman sambutan AdminLTE atau baseline tanpa broken link aset (HTTP 200). |
| **T1** | App Shell AdminLTE-4 | Base layout Blade AdminLTE-4: Topbar responsif, sidebar dinamis, breadcrumb, Dark/Light mode toggle, dan toast container. | Layout shell render mulus di mobile & desktop; toggle sidebar & dark mode berfungsi. |
| **T2** | Role & Permission Engine | Topbar Role Switcher (Warehouse Manager, Warehouse Staff, Purchasing, Finance, Super Admin) & visibilitas menu sesuai peran. | Ganti role langsung memperbarui badge peran aktif dan mengondisikan akses modul di antarmuka. |
| **T3** | Dataset Mock Terpusat | Store mock data terpusat (30+ item barang, 5 kategori, 3 gudang, master supplier, mutasi transaksi dummy). | Helper/store mock siap digunakan antarmodul dengan ID konsisten dan fungsi getter/query. |
| **T4** | Dashboard Inventaris | 4 KPI cards (Total SKU, Total Valuasi, Alert Stok, Mutasi Bulanan), chart mutasi masuk vs keluar, donat kategori, quick alerts. | Render KPI dan chart interaktif; angka stat konsisten dengan mock data. |
| **T5** | Master Data Barang & Kategori | Tabel barang dengan pencarian live search, filter kategori & stok, modal detail SKU, form tambah/edit barang dummy. | Filter dan pencarian berjalan instan; modal detail menampilkan spesifikasi lengkap & stok per gudang. |
| **T6** | Master Gudang, Rak & Supplier | Master data lokasi gudang (Utama, Transit, Retur), zonasi rak/bin, serta direktori vendor/supplier. | Tabel interaktif gudang dan supplier dengan modal informasi kontak dan kapasitas simpan. |
| **T7** | Penerimaan Barang (Inbound) | List Goods Receipt (GR), form input penerimaan barang dari supplier, modal cetak surat bukti penerimaan barang. | Form penerimaan dapat disimulasikan; status GR bertambah dan toast konfirmasi muncul. |
| **T8** | Pengeluaran & Mutasi (Outbound & Transfer) | List pengeluaran barang (DO dummy), form transfer antar gudang/rak, alur persetujuan mutasi stok. | Simulasi transfer barang dari Gudang A ke Gudang B memvalidasi ketersediaan dan mencatat riwayat. |
| **T9** | Stock Opname & Penyesuaian | Halaman jadwal & riwayat opname, form input selisih fisik vs sistem, alur approval penyesuaian stok (*write-off*). | Perhitungan selisih otomatis (surplus/minus); simulasi approval mengubah status opname. |
| **T10** | Kartu Stok & Pusat Laporan | Kartu stok per SKU (riwayat kronologis in/out/balance), filter laporan per periode & kategori, simulasi download PDF/Excel. | Kartu stok menampilkan timeline kronologis yang akurat; tombol ekspor memicu feedback realistis. |
| **T11** | Cross-Role QA & Final Demo Readiness | Pengujian end-to-end seluruh 5 peran, verifikasi semua route menghasilkan HTTP 200, bebas error konsol & Blade. | 100% route teruji 200 OK, navigasi konsisten, prototipe siap didemokan ke stakeholder. |
