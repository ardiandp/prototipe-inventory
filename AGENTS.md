# AGENTS.md — Agent Operating Guidelines & Architecture

Panduan operasional dan arsitektur sistem bagi AI Coding Agents yang bekerja pada repositori `PORTO/inventory`.

---

## 1. Aturan Dasar Pengembangan (Core Constraints)

1. **Dilarang Membuat Database Fisik atau Migration**:
   - Repositori ini adalah **High-Fidelity Interactive UI Prototype**.
   - Dilarang membuat koneksi database MySQL/PostgreSQL/SQLite, menjalankan `php artisan migrate`, atau membuat tabel database bisnis.
   - Semua manipulasi data harus menggunakan mock data store terpusat pada `app/Support/MockDataStore.php` dan simulasi interaktif di sisi klien (Blade & JavaScript).

2. **Aturan Eksekusi Task Berurutan**:
   - Kerjakan hanya satu task per interaksi/giliran sesuai instruksi pengguna.
   - Setiap task wajib melalui acceptance gate: implementasi kode, pengujian (syntax linting & HTTP route verification), pencatatan di `PROGRESS.md`, dan pembuatan git commit tersendiri.

3. **Standar Desain Antarmuka**:
   - Gunakan tema **AdminLTE 4** berbasis **Bootstrap 5.3** yang berlokasi di `inventory-prototipe/public/theme/AdminLTE-4`.
   - Gunakan helper path aset: `asset('theme/AdminLTE-4/dist/...')`.
   - Gunakan ikon Bootstrap Icons (`bi bi-*`).
   - Gunakan helper notifikasi toast global: `window.showToast(message, type, title)`.

---

## 2. Struktur Direktori Proyek

```
PORTO/inventory/
├── docs/                        # Dokumentasi spesifikasi & audit
│   ├── AUDIT_REPORT.md          # Laporan audit baseline T0.1
│   └── PRD_PROTOTYPE.md         # Spesifikasi kebutuhan prototipe
├── inventory-prototipe/         # Aplikasi Laravel 13 utama
│   ├── app/
│   │   ├── Support/
│   │   │   ├── MockDataStore.php # Store data mock terpusat
│   │   │   └── RoleManager.php   # RBAC & Role Switcher Engine (5 peran)
│   │   └── Providers/
│   │       └── AppServiceProvider.php # View Composers (stats, active role)
│   ├── public/
│   │   └── theme/AdminLTE-4/    # Aset statis AdminLTE 4 (CSS, JS, assets)
│   ├── resources/views/
│   │   ├── layouts/             # App Shell AdminLTE-4 (topbar, sidebar, header, footer)
│   │   ├── dashboard/           # Dashboard analitik & KPI
│   │   ├── master/              # Master data (barang, lokasi rak, supplier)
│   │   ├── operasional/         # Operasional stok (inbound, outbound, opname)
│   │   ├── laporan/             # Pusat laporan (kartu stok digital, mutasi valuasi)
│   │   └── pengaturan/          # Pengaturan prototipe & matriks otorisasi
│   └── routes/web.php           # Routing aplikasi
├── index.php                    # Root entrypoint forwarder untuk vhost Laragon
├── IMPLEMENTATION_PLAN.md       # Roadmap implementasi T0.1 s/d T11
├── PROGRESS.md                  # Log progres & tracking commit hash
├── README.md                    # Dokumentasi utama proyek
└── AGENTS.md                    # Panduan operasional agent (file ini)
```

---

## 3. Matriks Otorisasi Pengguna (Role-based Access Control)

Engine otorisasi diimplementasikan di `App\Support\RoleManager`:
- `wh_manager`: Warehouse Manager (`Budi Santoso`) — Akses penuh ke seluruh modul dan approval.
- `wh_staff`: Warehouse Staff (`Joko Prasetyo`) — Akses operasional harian (Inbound, Outbound, Opname).
- `purchasing`: Purchasing / Buyer (`Siti Rahma`) — Akses master vendor, barang, inbound, dan kartu stok.
- `finance`: Finance / Auditor (`Hendra Wijaya`) — Akses audit valuasi persediaan, review opname, dan persetujuan selisih.
- `admin`: Super Admin (`Bambang Sugiarto`) — Akses pengawasan sistem menyeluruh.

Pemeriksaan hak akses di view Blade menggunakan:
```blade
@if(\App\Support\RoleManager::can('permission_key'))
  <!-- Elemen aksi berwenang -->
@endif
```

---

## 4. Eksekusi Perintah & PHP di Environment Windows Laragon

Pada environment Windows ini, biner PHP Laragon terletak di:
```powershell
& "C:\laragon\bin\php\8.3.28\php.exe" ...
```
Hindari memanggil perintah `php` telanjang karena PATH sistem global mungkin belum mengarah ke biner Laragon.
Gunakan biner tersebut untuk linting (`-l`) dan menjalankan skrip verifikasi route HTTP.

