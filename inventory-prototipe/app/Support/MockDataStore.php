<?php

namespace App\Support;

class MockDataStore
{
    /**
     * Master Kategori Barang (5 Kategori)
     */
    public static function getCategories(): array
    {
        return [
            ['id' => 'KAT-01', 'name' => 'Bahan Baku & Logam', 'code' => 'RAW', 'icon' => 'bi-boxes', 'color' => 'primary', 'desc' => 'Lembaran logam, plat, aluminium, dan material fabrikasi'],
            ['id' => 'KAT-02', 'name' => 'Elektronik & Sparepart', 'code' => 'ELK', 'icon' => 'bi-cpu', 'color' => 'info', 'desc' => 'Sensor, IC, modul sirkuit, kabel industri, dan komponen kelistrikan'],
            ['id' => 'KAT-03', 'name' => 'Packaging & Kemasan', 'code' => 'PKG', 'icon' => 'bi-box-seam', 'color' => 'warning', 'desc' => 'Karton box, bubble wrap, stretch film, dan label barcode'],
            ['id' => 'KAT-04', 'name' => 'Alat & Safety Gear', 'code' => 'SFT', 'icon' => 'bi-shield-shaded', 'color' => 'success', 'desc' => 'APD, sarung tangan, helm kerja, sepatu safety, dan perkakas'],
            ['id' => 'KAT-05', 'name' => 'Produk Jadi (Finish Goods)', 'code' => 'FNG', 'icon' => 'bi-check-all', 'color' => 'danger', 'desc' => 'Barang siap kirim dan produk rakitan final'],
        ];
    }

    /**
     * Master Lokasi Gudang & Rak (3 Gudang)
     */
    public static function getWarehouses(): array
    {
        return [
            [
                'id' => 'GDG-01',
                'name' => 'Gudang Utama Logistik',
                'code' => 'WH-CKR-01',
                'city' => 'Cikarang, Jawa Barat',
                'type' => 'Pusat Distribusi',
                'capacity_pct' => 78,
                'total_bins' => 48,
                'manager' => 'Budi Santoso',
                'racks' => ['Rak A1-01', 'Rak A1-02', 'Rak B2-01', 'Rak B2-02', 'Rak C3-01', 'Rak D4-01']
            ],
            [
                'id' => 'GDG-02',
                'name' => 'Gudang Transit & Staging',
                'code' => 'WH-KRW-02',
                'city' => 'Karawang Timur',
                'type' => 'Transit & Buffer',
                'capacity_pct' => 54,
                'total_bins' => 24,
                'manager' => 'Joko Prasetyo',
                'racks' => ['Rak T1-01', 'Rak T1-02', 'Rak T2-01', 'Rak T2-02']
            ],
            [
                'id' => 'GDG-03',
                'name' => 'Gudang Retur & Karantina',
                'code' => 'WH-BKS-03',
                'city' => 'Bekasi Barat',
                'type' => 'Karantina & QC',
                'capacity_pct' => 32,
                'total_bins' => 16,
                'manager' => 'Hendra Wijaya',
                'racks' => ['Rak R1-01', 'Rak R1-02', 'Rak R2-01']
            ],
        ];
    }

    /**
     * Master Supplier & Vendor (5 Pemasok)
     */
    public static function getSuppliers(): array
    {
        return [
            ['id' => 'SUP-001', 'name' => 'PT Mitra Logam Presisi', 'category' => 'Bahan Baku & Logam', 'contact' => 'Ir. Hartono (0812-8899-1122)', 'email' => 'sales@mitralogam.co.id', 'city' => 'Surabaya', 'rating' => 4.9],
            ['id' => 'SUP-002', 'name' => 'PT Global Semiconductor Tech', 'category' => 'Elektronik & Sparepart', 'contact' => 'Denny Salim (0813-4455-6677)', 'email' => 'order@globalsemi.com', 'city' => 'Jakarta Barat', 'rating' => 4.8],
            ['id' => 'SUP-003', 'name' => 'CV Multi Pack Mandiri', 'category' => 'Packaging & Kemasan', 'contact' => 'Dewi Lestari (0817-2233-4455)', 'email' => 'cs@multipack.id', 'city' => 'Tangerang', 'rating' => 4.7],
            ['id' => 'SUP-004', 'name' => 'PT Krakatau Baja Industri', 'category' => 'Bahan Baku & Logam', 'contact' => 'Bambang Supeno (0811-9988-7766)', 'email' => 'industrial@kbi.co.id', 'city' => 'Cilegon', 'rating' => 4.9],
            ['id' => 'SUP-005', 'name' => 'PT Delta Safety Equipment', 'category' => 'Alat & Safety Gear', 'contact' => 'Rudy Pratama (0856-1122-3344)', 'email' => 'support@deltasafety.com', 'city' => 'Bekasi', 'rating' => 4.6],
        ];
    }

    /**
     * Master Data Barang / SKU (32 Item Realistis)
     */
    public static function getItems(): array
    {
        return [
            // Kategori 1: Bahan Baku & Logam
            [
                'id' => 1,
                'sku' => 'SKU-RAW-001',
                'name' => 'Aluminium Sheet 2.0mm 4x8 ft',
                'category_id' => 'KAT-01',
                'category_name' => 'Bahan Baku & Logam',
                'uom' => 'Lembar',
                'min_stock' => 20,
                'max_stock' => 150,
                'current_stock' => 64,
                'buy_price' => 420000,
                'sell_price' => 520000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak A1-01',
                'status' => 'Aman',
            ],
            [
                'id' => 2,
                'sku' => 'SKU-RAW-002',
                'name' => 'Stainless Steel Rod SUS304 Dia 25mm',
                'category_id' => 'KAT-01',
                'category_name' => 'Bahan Baku & Logam',
                'uom' => 'Batang',
                'min_stock' => 30,
                'max_stock' => 200,
                'current_stock' => 88,
                'buy_price' => 275000,
                'sell_price' => 340000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak A1-02',
                'status' => 'Aman',
            ],
            [
                'id' => 3,
                'sku' => 'SKU-RAW-003',
                'name' => 'Plat Baja Karbon Hitam 3.0mm',
                'category_id' => 'KAT-01',
                'category_name' => 'Bahan Baku & Logam',
                'uom' => 'Lembar',
                'min_stock' => 25,
                'max_stock' => 100,
                'current_stock' => 12,
                'buy_price' => 560000,
                'sell_price' => 690000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak A1-02',
                'status' => 'Menipis',
            ],
            [
                'id' => 4,
                'sku' => 'SKU-RAW-004',
                'name' => 'Pipa Tembaga Seamless 1/2 inch',
                'category_id' => 'KAT-01',
                'category_name' => 'Bahan Baku & Logam',
                'uom' => 'Roll',
                'min_stock' => 15,
                'max_stock' => 80,
                'current_stock' => 35,
                'buy_price' => 820000,
                'sell_price' => 980000,
                'warehouse_id' => 'GDG-02',
                'warehouse_name' => 'Gudang Transit & Staging',
                'bin_location' => 'Rak T1-01',
                'status' => 'Aman',
            ],
            [
                'id' => 5,
                'sku' => 'SKU-RAW-005',
                'name' => 'Kawat Las Kuningan 2.5mm',
                'category_id' => 'KAT-01',
                'category_name' => 'Bahan Baku & Logam',
                'uom' => 'Kg',
                'min_stock' => 50,
                'max_stock' => 300,
                'current_stock' => 140,
                'buy_price' => 135000,
                'sell_price' => 170000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-01',
                'status' => 'Aman',
            ],
            [
                'id' => 6,
                'sku' => 'SKU-RAW-006',
                'name' => 'Besi Siku Galvanis 40x40x4mm 6m',
                'category_id' => 'KAT-01',
                'category_name' => 'Bahan Baku & Logam',
                'uom' => 'Batang',
                'min_stock' => 40,
                'max_stock' => 250,
                'current_stock' => 8,
                'buy_price' => 180000,
                'sell_price' => 230000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak A1-01',
                'status' => 'Menipis',
            ],

            // Kategori 2: Elektronik & Sparepart
            [
                'id' => 7,
                'sku' => 'SKU-ELK-001',
                'name' => 'Microcontroller Board ESP32-WROOM-32',
                'category_id' => 'KAT-02',
                'category_name' => 'Elektronik & Sparepart',
                'uom' => 'Pcs',
                'min_stock' => 50,
                'max_stock' => 500,
                'current_stock' => 210,
                'buy_price' => 62000,
                'sell_price' => 85000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-02',
                'status' => 'Aman',
            ],
            [
                'id' => 8,
                'sku' => 'SKU-ELK-002',
                'name' => 'Industrial Optical Proximity Sensor NPN',
                'category_id' => 'KAT-02',
                'category_name' => 'Elektronik & Sparepart',
                'uom' => 'Pcs',
                'min_stock' => 20,
                'max_stock' => 120,
                'current_stock' => 42,
                'buy_price' => 195000,
                'sell_price' => 260000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-02',
                'status' => 'Aman',
            ],
            [
                'id' => 9,
                'sku' => 'SKU-ELK-003',
                'name' => 'Power Supply Switching 24V 10A DIN Rail',
                'category_id' => 'KAT-02',
                'category_name' => 'Elektronik & Sparepart',
                'uom' => 'Unit',
                'min_stock' => 15,
                'max_stock' => 80,
                'current_stock' => 28,
                'buy_price' => 310000,
                'sell_price' => 415000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-01',
                'status' => 'Aman',
            ],
            [
                'id' => 10,
                'sku' => 'SKU-ELK-004',
                'name' => 'Stepper Motor NEMA 23 High Torque',
                'category_id' => 'KAT-02',
                'category_name' => 'Elektronik & Sparepart',
                'uom' => 'Unit',
                'min_stock' => 10,
                'max_stock' => 60,
                'current_stock' => 3,
                'buy_price' => 240000,
                'sell_price' => 320000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-02',
                'status' => 'Menipis',
            ],
            [
                'id' => 11,
                'sku' => 'SKU-ELK-005',
                'name' => 'Kabel Control Shielded 4x0.75mm',
                'category_id' => 'KAT-02',
                'category_name' => 'Elektronik & Sparepart',
                'uom' => 'Meter',
                'min_stock' => 100,
                'max_stock' => 1000,
                'current_stock' => 520,
                'buy_price' => 14500,
                'sell_price' => 21000,
                'warehouse_id' => 'GDG-02',
                'warehouse_name' => 'Gudang Transit & Staging',
                'bin_location' => 'Rak T1-02',
                'status' => 'Aman',
            ],
            [
                'id' => 12,
                'sku' => 'SKU-ELK-006',
                'name' => 'Solid State Relay SSR-40DA 40A',
                'category_id' => 'KAT-02',
                'category_name' => 'Elektronik & Sparepart',
                'uom' => 'Pcs',
                'min_stock' => 25,
                'max_stock' => 150,
                'current_stock' => 0,
                'buy_price' => 78000,
                'sell_price' => 110000,
                'warehouse_id' => 'GDG-03',
                'warehouse_name' => 'Gudang Retur & Karantina',
                'bin_location' => 'Rak R1-01',
                'status' => 'Habis',
            ],
            [
                'id' => 13,
                'sku' => 'SKU-ELK-007',
                'name' => 'Temperature Controller PID REX-C100',
                'category_id' => 'KAT-02',
                'category_name' => 'Elektronik & Sparepart',
                'uom' => 'Set',
                'min_stock' => 15,
                'max_stock' => 90,
                'current_stock' => 31,
                'buy_price' => 165000,
                'sell_price' => 225000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-01',
                'status' => 'Aman',
            ],

            // Kategori 3: Packaging & Kemasan
            [
                'id' => 14,
                'sku' => 'SKU-PKG-001',
                'name' => 'Karton Box Corrugated Double Wall 40x30x30cm',
                'category_id' => 'KAT-03',
                'category_name' => 'Packaging & Kemasan',
                'uom' => 'Pcs',
                'min_stock' => 100,
                'max_stock' => 1000,
                'current_stock' => 450,
                'buy_price' => 16500,
                'sell_price' => 23000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak C3-01',
                'status' => 'Aman',
            ],
            [
                'id' => 15,
                'sku' => 'SKU-PKG-002',
                'name' => 'Stretch Film Plastik Wrapping 50cm x 300m',
                'category_id' => 'KAT-03',
                'category_name' => 'Packaging & Kemasan',
                'uom' => 'Roll',
                'min_stock' => 30,
                'max_stock' => 200,
                'current_stock' => 84,
                'buy_price' => 72000,
                'sell_price' => 95000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak C3-01',
                'status' => 'Aman',
            ],
            [
                'id' => 16,
                'sku' => 'SKU-PKG-003',
                'name' => 'Bubble Wrap Heavy Duty 125cm x 50m',
                'category_id' => 'KAT-03',
                'category_name' => 'Packaging & Kemasan',
                'uom' => 'Roll',
                'min_stock' => 20,
                'max_stock' => 150,
                'current_stock' => 9,
                'buy_price' => 125000,
                'sell_price' => 165000,
                'warehouse_id' => 'GDG-02',
                'warehouse_name' => 'Gudang Transit & Staging',
                'bin_location' => 'Rak T2-01',
                'status' => 'Menipis',
            ],
            [
                'id' => 17,
                'sku' => 'SKU-PKG-004',
                'name' => 'Lakban Fragile Merah Putih 48mm x 90y',
                'category_id' => 'KAT-03',
                'category_name' => 'Packaging & Kemasan',
                'uom' => 'Roll',
                'min_stock' => 50,
                'max_stock' => 400,
                'current_stock' => 195,
                'buy_price' => 13500,
                'sell_price' => 19000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak C3-01',
                'status' => 'Aman',
            ],
            [
                'id' => 18,
                'sku' => 'SKU-PKG-005',
                'name' => 'Thermal Shipping Label Barcode 100x150mm',
                'category_id' => 'KAT-03',
                'category_name' => 'Packaging & Kemasan',
                'uom' => 'Roll',
                'min_stock' => 40,
                'max_stock' => 300,
                'current_stock' => 160,
                'buy_price' => 48000,
                'sell_price' => 68000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak C3-01',
                'status' => 'Aman',
            ],
            [
                'id' => 19,
                'sku' => 'SKU-PKG-006',
                'name' => 'Pallet Kayu Standar Ekspor ISPM-15 120x100cm',
                'category_id' => 'KAT-03',
                'category_name' => 'Packaging & Kemasan',
                'uom' => 'Unit',
                'min_stock' => 30,
                'max_stock' => 200,
                'current_stock' => 75,
                'buy_price' => 140000,
                'sell_price' => 185000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak D4-01',
                'status' => 'Aman',
            ],

            // Kategori 4: Alat & Safety Gear
            [
                'id' => 20,
                'sku' => 'SKU-SFT-001',
                'name' => 'Helm Proyek MSA V-Gard White SNI',
                'category_id' => 'KAT-04',
                'category_name' => 'Alat & Safety Gear',
                'uom' => 'Pcs',
                'min_stock' => 25,
                'max_stock' => 150,
                'current_stock' => 62,
                'buy_price' => 85000,
                'sell_price' => 120000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak D4-01',
                'status' => 'Aman',
            ],
            [
                'id' => 21,
                'sku' => 'SKU-SFT-002',
                'name' => 'Sepatu Safety Steel Toe Cap Ukuran 42',
                'category_id' => 'KAT-04',
                'category_name' => 'Alat & Safety Gear',
                'uom' => 'Pasang',
                'min_stock' => 15,
                'max_stock' => 80,
                'current_stock' => 38,
                'buy_price' => 265000,
                'sell_price' => 350000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak D4-01',
                'status' => 'Aman',
            ],
            [
                'id' => 22,
                'sku' => 'SKU-SFT-003',
                'name' => 'Sarung Tangan Nitrile Chemical Resistant',
                'category_id' => 'KAT-04',
                'category_name' => 'Alat & Safety Gear',
                'uom' => 'Pasang',
                'min_stock' => 50,
                'max_stock' => 350,
                'current_stock' => 18,
                'buy_price' => 32000,
                'sell_price' => 48000,
                'warehouse_id' => 'GDG-02',
                'warehouse_name' => 'Gudang Transit & Staging',
                'bin_location' => 'Rak T2-02',
                'status' => 'Menipis',
            ],
            [
                'id' => 23,
                'sku' => 'SKU-SFT-004',
                'name' => 'Rompi Safety Jaring Hijau Stabilo Reflective',
                'category_id' => 'KAT-04',
                'category_name' => 'Alat & Safety Gear',
                'uom' => 'Pcs',
                'min_stock' => 30,
                'max_stock' => 200,
                'current_stock' => 110,
                'buy_price' => 28000,
                'sell_price' => 42000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak D4-01',
                'status' => 'Aman',
            ],
            [
                'id' => 24,
                'sku' => 'SKU-SFT-005',
                'name' => 'Digital Vernier Caliper 150mm Stainless',
                'category_id' => 'KAT-04',
                'category_name' => 'Alat & Safety Gear',
                'uom' => 'Unit',
                'min_stock' => 10,
                'max_stock' => 50,
                'current_stock' => 24,
                'buy_price' => 185000,
                'sell_price' => 250000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak D4-01',
                'status' => 'Aman',
            ],
            [
                'id' => 25,
                'sku' => 'SKU-SFT-006',
                'name' => 'Safety Glasses Polycarbonate Anti-Fog',
                'category_id' => 'KAT-04',
                'category_name' => 'Alat & Safety Gear',
                'uom' => 'Pcs',
                'min_stock' => 30,
                'max_stock' => 180,
                'current_stock' => 95,
                'buy_price' => 35000,
                'sell_price' => 52000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak D4-01',
                'status' => 'Aman',
            ],

            // Kategori 5: Produk Jadi (Finish Goods)
            [
                'id' => 26,
                'sku' => 'SKU-FNG-001',
                'name' => 'Panel Box Kontrol Otomasi 60x40x20cm',
                'category_id' => 'KAT-05',
                'category_name' => 'Produk Jadi (Finish Goods)',
                'uom' => 'Unit',
                'min_stock' => 8,
                'max_stock' => 40,
                'current_stock' => 18,
                'buy_price' => 1850000,
                'sell_price' => 2650000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-01',
                'status' => 'Aman',
            ],
            [
                'id' => 27,
                'sku' => 'SKU-FNG-002',
                'name' => 'Conveyor Belt Mini 1500mm Motorized',
                'category_id' => 'KAT-05',
                'category_name' => 'Produk Jadi (Finish Goods)',
                'uom' => 'Unit',
                'min_stock' => 5,
                'max_stock' => 25,
                'current_stock' => 7,
                'buy_price' => 3400000,
                'sell_price' => 4800000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-01',
                'status' => 'Aman',
            ],
            [
                'id' => 28,
                'sku' => 'SKU-FNG-003',
                'name' => 'Smart IoT Gateway Box IP65 Industrial',
                'category_id' => 'KAT-05',
                'category_name' => 'Produk Jadi (Finish Goods)',
                'uom' => 'Unit',
                'min_stock' => 12,
                'max_stock' => 70,
                'current_stock' => 2,
                'buy_price' => 950000,
                'sell_price' => 1450000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-02',
                'status' => 'Menipis',
            ],
            [
                'id' => 29,
                'sku' => 'SKU-FNG-004',
                'name' => 'Modul Power Inverter 1000W Pure Sine Wave',
                'category_id' => 'KAT-05',
                'category_name' => 'Produk Jadi (Finish Goods)',
                'uom' => 'Unit',
                'min_stock' => 10,
                'max_stock' => 60,
                'current_stock' => 24,
                'buy_price' => 780000,
                'sell_price' => 1150000,
                'warehouse_id' => 'GDG-02',
                'warehouse_name' => 'Gudang Transit & Staging',
                'bin_location' => 'Rak T1-01',
                'status' => 'Aman',
            ],
            [
                'id' => 30,
                'sku' => 'SKU-FNG-005',
                'name' => 'Pneumatic Press Jig Tooling Assembly',
                'category_id' => 'KAT-05',
                'category_name' => 'Produk Jadi (Finish Goods)',
                'uom' => 'Set',
                'min_stock' => 5,
                'max_stock' => 30,
                'current_stock' => 11,
                'buy_price' => 2200000,
                'sell_price' => 3100000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak D4-01',
                'status' => 'Aman',
            ],
            [
                'id' => 31,
                'sku' => 'SKU-FNG-006',
                'name' => 'Digital Inspection Scale 30Kg Calibrated',
                'category_id' => 'KAT-05',
                'category_name' => 'Produk Jadi (Finish Goods)',
                'uom' => 'Unit',
                'min_stock' => 6,
                'max_stock' => 35,
                'current_stock' => 14,
                'buy_price' => 650000,
                'sell_price' => 920000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak D4-01',
                'status' => 'Aman',
            ],
            [
                'id' => 32,
                'sku' => 'SKU-FNG-007',
                'name' => 'Solar Panel Charge Regulator 12V/24V 30A',
                'category_id' => 'KAT-05',
                'category_name' => 'Produk Jadi (Finish Goods)',
                'uom' => 'Unit',
                'min_stock' => 15,
                'max_stock' => 90,
                'current_stock' => 45,
                'buy_price' => 320000,
                'sell_price' => 460000,
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-02',
                'status' => 'Aman',
            ]
        ];
    }

    /**
     * Riwayat Transaksi Mutasi Stok (20 Transaksi Terkini)
     */
    public static function getTransactions(): array
    {
        return [
            ['id' => 'TRX-2026-001', 'date' => '2026-11-12 14:20', 'type' => 'inbound', 'type_label' => 'Penerimaan (GR)', 'sku' => 'SKU-RAW-001', 'item_name' => 'Aluminium Sheet 2.0mm', 'qty' => 30, 'uom' => 'Lembar', 'ref_no' => 'GR-2026-004', 'party' => 'PT Mitra Logam Presisi', 'location' => 'Gudang Utama (A1-01)', 'operator' => 'Joko Prasetyo', 'status' => 'Completed'],
            ['id' => 'TRX-2026-002', 'date' => '2026-11-12 11:45', 'type' => 'outbound', 'type_label' => 'Pengeluaran (DO)', 'sku' => 'SKU-ELK-001', 'item_name' => 'Microcontroller Board ESP32', 'qty' => 40, 'uom' => 'Pcs', 'ref_no' => 'DO-2026-081', 'party' => 'Divisi Produksi IoT', 'location' => 'Gudang Utama (B2-02)', 'operator' => 'Joko Prasetyo', 'status' => 'Completed'],
            ['id' => 'TRX-2026-003', 'date' => '2026-11-11 16:10', 'type' => 'transfer', 'type_label' => 'Transfer Antar Gudang', 'sku' => 'SKU-PKG-003', 'item_name' => 'Bubble Wrap Heavy Duty', 'qty' => 15, 'uom' => 'Roll', 'ref_no' => 'TF-2026-019', 'party' => 'Gudang Utama -> Transit', 'location' => 'Karawang Hub (T2-01)', 'operator' => 'Budi Santoso', 'status' => 'Completed'],
            ['id' => 'TRX-2026-004', 'date' => '2026-11-11 13:30', 'type' => 'inbound', 'type_label' => 'Penerimaan (GR)', 'sku' => 'SKU-ELK-003', 'item_name' => 'Power Supply 24V 10A', 'qty' => 20, 'uom' => 'Unit', 'ref_no' => 'GR-2026-003', 'party' => 'PT Global Semiconductor Tech', 'location' => 'Gudang Utama (B2-01)', 'operator' => 'Joko Prasetyo', 'status' => 'Completed'],
            ['id' => 'TRX-2026-005', 'date' => '2026-11-10 15:00', 'type' => 'adjustment', 'type_label' => 'Penyesuaian Opname', 'sku' => 'SKU-PKG-001', 'item_name' => 'Karton Box Corrugated', 'qty' => 10, 'uom' => 'Pcs', 'ref_no' => 'OPN-2026-11-08', 'party' => 'Selisih Fisik Gudang (+)', 'location' => 'Gudang Utama (C3-01)', 'operator' => 'Hendra Wijaya', 'status' => 'Approved'],
            ['id' => 'TRX-2026-006', 'date' => '2026-11-10 10:20', 'type' => 'outbound', 'type_label' => 'Pengeluaran (DO)', 'sku' => 'SKU-FNG-001', 'item_name' => 'Panel Box Kontrol Otomasi', 'qty' => 4, 'uom' => 'Unit', 'ref_no' => 'DO-2026-080', 'party' => 'PT Astra Otoparts', 'location' => 'Gudang Utama (B2-01)', 'operator' => 'Joko Prasetyo', 'status' => 'Completed'],
            ['id' => 'TRX-2026-007', 'date' => '2026-11-09 14:15', 'type' => 'inbound', 'type_label' => 'Penerimaan (GR)', 'sku' => 'SKU-SFT-001', 'item_name' => 'Helm Proyek MSA V-Gard', 'qty' => 50, 'uom' => 'Pcs', 'ref_no' => 'GR-2026-002', 'party' => 'PT Delta Safety Equipment', 'location' => 'Gudang Utama (D4-01)', 'operator' => 'Joko Prasetyo', 'status' => 'Completed'],
            ['id' => 'TRX-2026-008', 'date' => '2026-11-09 09:30', 'type' => 'transfer', 'type_label' => 'Transfer Antar Gudang', 'sku' => 'SKU-RAW-004', 'item_name' => 'Pipa Tembaga Seamless', 'qty' => 10, 'uom' => 'Roll', 'ref_no' => 'TF-2026-018', 'party' => 'Transit -> Gudang Utama', 'location' => 'Cikarang Central (A1-02)', 'operator' => 'Budi Santoso', 'status' => 'Completed'],
            ['id' => 'TRX-2026-009', 'date' => '2026-11-08 16:40', 'type' => 'outbound', 'type_label' => 'Pengeluaran (DO)', 'sku' => 'SKU-RAW-002', 'item_name' => 'Stainless Steel Rod SUS304', 'qty' => 25, 'uom' => 'Batang', 'ref_no' => 'DO-2026-079', 'party' => 'Divisi CNC Machining', 'location' => 'Gudang Utama (A1-02)', 'operator' => 'Joko Prasetyo', 'status' => 'Completed'],
            ['id' => 'TRX-2026-010', 'date' => '2026-11-08 11:00', 'type' => 'inbound', 'type_label' => 'Penerimaan (GR)', 'sku' => 'SKU-PKG-005', 'item_name' => 'Thermal Shipping Label', 'qty' => 80, 'uom' => 'Roll', 'ref_no' => 'GR-2026-001', 'party' => 'CV Multi Pack Mandiri', 'location' => 'Gudang Utama (C3-01)', 'operator' => 'Joko Prasetyo', 'status' => 'Completed'],
            ['id' => 'TRX-2026-011', 'date' => '2026-11-07 15:20', 'type' => 'outbound', 'type_label' => 'Pengeluaran (DO)', 'sku' => 'SKU-FNG-002', 'item_name' => 'Conveyor Belt Mini', 'qty' => 2, 'uom' => 'Unit', 'ref_no' => 'DO-2026-078', 'party' => 'PT Mayora Indah Tbk', 'location' => 'Gudang Utama (B2-01)', 'operator' => 'Joko Prasetyo', 'status' => 'Completed'],
            ['id' => 'TRX-2026-012', 'date' => '2026-11-07 10:10', 'type' => 'adjustment', 'type_label' => 'Penyesuaian Opname', 'sku' => 'SKU-ELK-006', 'item_name' => 'Solid State Relay SSR-40DA', 'qty' => -2, 'uom' => 'Pcs', 'ref_no' => 'OPN-2026-11-01', 'party' => 'Kerusakan fisik / QC Reject', 'location' => 'Gudang Retur (R1-01)', 'operator' => 'Hendra Wijaya', 'status' => 'Approved'],
            ['id' => 'TRX-2026-013', 'date' => '2026-11-06 14:00', 'type' => 'inbound', 'type_label' => 'Penerimaan (GR)', 'sku' => 'SKU-RAW-005', 'item_name' => 'Kawat Las Kuningan', 'qty' => 60, 'uom' => 'Kg', 'ref_no' => 'GR-2026-000', 'party' => 'PT Krakatau Baja Industri', 'location' => 'Gudang Utama (B2-01)', 'operator' => 'Joko Prasetyo', 'status' => 'Completed'],
            ['id' => 'TRX-2026-014', 'date' => '2026-11-06 09:15', 'type' => 'outbound', 'type_label' => 'Pengeluaran (DO)', 'sku' => 'SKU-SFT-002', 'item_name' => 'Sepatu Safety Steel Toe', 'qty' => 12, 'uom' => 'Pasang', 'ref_no' => 'DO-2026-077', 'party' => 'Distribusi K3 Pabrik', 'location' => 'Gudang Utama (D4-01)', 'operator' => 'Joko Prasetyo', 'status' => 'Completed'],
            ['id' => 'TRX-2026-015', 'date' => '2026-11-05 13:45', 'type' => 'transfer', 'type_label' => 'Transfer Antar Gudang', 'sku' => 'SKU-ELK-005', 'item_name' => 'Kabel Control Shielded', 'qty' => 150, 'uom' => 'Meter', 'ref_no' => 'TF-2026-017', 'party' => 'Gudang Utama -> Transit', 'location' => 'Karawang Hub (T1-02)', 'operator' => 'Budi Santoso', 'status' => 'Completed'],
        ];
    }

    /**
     * Sesi Stock Opname & Penyesuaian
     */
    public static function getOpnameSessions(): array
    {
        return [
            [
                'id' => 'OPN-2026-11-01',
                'title' => 'Stock Opname Awal Bulan November 2026',
                'date' => '2026-11-01',
                'warehouse' => 'Gudang Utama Logistik',
                'auditor' => 'Hendra Wijaya',
                'total_items_checked' => 32,
                'match_count' => 30,
                'discrepancy_count' => 2,
                'status' => 'Selesai & Disetujui',
                'notes' => 'Ditemukan selisih 2 pcs SSR reject dan penyesuaian saldo karton box.'
            ],
            [
                'id' => 'OPN-2026-11-08',
                'title' => 'Audit Rutin Mingguan Gudang Karawang',
                'date' => '2026-11-08',
                'warehouse' => 'Gudang Transit & Staging',
                'auditor' => 'Hendra Wijaya',
                'total_items_checked' => 18,
                'match_count' => 18,
                'discrepancy_count' => 0,
                'status' => 'Selesai Sempurna',
                'notes' => '100% fisik sesuai dengan kartu stok digital.'
            ],
            [
                'id' => 'OPN-2026-11-12',
                'title' => 'Stock Opname Parsial Bahan Logam & Fast Moving',
                'date' => '2026-11-12',
                'warehouse' => 'Gudang Utama Logistik',
                'auditor' => 'Hendra Wijaya',
                'total_items_checked' => 10,
                'match_count' => 9,
                'discrepancy_count' => 1,
                'status' => 'Menunggu Approval Manager',
                'notes' => 'Selisih minus 1 lembar Plat Baja Karbon dalam proses konfirmasi bukti surat jalan.'
            ]
        ];
    }

    /**
     * Ringkasan Statistik KPI
     */
    public static function getSummaryStats(): array
    {
        $items = self::getItems();
        $totalSku = count($items);
        $totalValuation = 0;
        $lowStockCount = 0;
        $outOfStockCount = 0;

        foreach ($items as $item) {
            $totalValuation += ($item['current_stock'] * $item['buy_price']);
            if ($item['current_stock'] == 0) {
                $outOfStockCount++;
            } elseif ($item['current_stock'] <= $item['min_stock']) {
                $lowStockCount++;
            }
        }

        $transactions = self::getTransactions();
        $monthlyMovements = 0;
        foreach ($transactions as $t) {
            $monthlyMovements += abs($t['qty']);
        }

        return [
            'total_sku' => $totalSku,
            'total_valuation' => $totalValuation,
            'total_valuation_formatted' => 'Rp ' . number_format($totalValuation, 0, ',', '.'),
            'low_stock_count' => $lowStockCount,
            'out_of_stock_count' => $outOfStockCount,
            'alert_count' => $lowStockCount + $outOfStockCount,
            'monthly_movements' => $monthlyMovements,
            'total_warehouses' => count(self::getWarehouses()),
            'total_suppliers' => count(self::getSuppliers()),
            'total_categories' => count(self::getCategories())
        ];
    }

    /**
     * Dapatkan item berdasarkan SKU
     */
    public static function getItemBySku(string $sku): ?array
    {
        foreach (self::getItems() as $item) {
            if ($item['sku'] === $sku) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Data Grafik Tren Mutasi Mingguan (Chart.js / AdminLTE)
     */
    public static function getChartData(): array
    {
        return [
            'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            'inbound' => [120, 190, 150, 220, 180, 90, 40],
            'outbound' => [90, 140, 170, 210, 240, 110, 30],
            'categories' => [
                'labels' => ['Bahan Baku', 'Elektronik', 'Packaging', 'Safety Gear', 'Finish Goods'],
                'counts' => [6, 7, 6, 6, 7],
                'colors' => ['#0d6efd', '#0dcaf0', '#ffc107', '#198754', '#dc3545']
            ]
        ];
    }

    /**
     * Data Transaksi Penerimaan Barang (Goods Receipt / Inbound)
     */
    public static function getInboundReceipts(): array
    {
        return [
            [
                'id' => 'GR-2026-004',
                'po_number' => 'PO-2026-108',
                'delivery_order_no' => 'SJ-MPL-8821',
                'date' => '2026-11-12 14:20',
                'supplier_id' => 'SPL-01',
                'supplier_name' => 'PT Mitra Logam Presisi',
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak A1-01 / A1-02',
                'receiver' => 'Joko Prasetyo',
                'qc_inspector' => 'Hendra Wijaya',
                'qc_status' => 'Passed QC',
                'status' => 'COMPLETED',
                'status_label' => 'Selesai & Masuk Stok',
                'status_badge' => 'success',
                'notes' => 'Penerimaan bahan baku logam sesuai sertifikat mill spec JIS G3141.',
                'total_qty' => 50,
                'total_valuation' => 18250000,
                'items' => [
                    [
                        'sku' => 'SKU-RAW-001',
                        'name' => 'Aluminium Sheet 2.0mm x 1200 x 2400',
                        'qty_ordered' => 30,
                        'qty_received' => 30,
                        'qty_rejected' => 0,
                        'uom' => 'Lembar',
                        'unit_price' => 425000,
                        'subtotal' => 12750000,
                        'bin' => 'Rak A1-01',
                        'condition' => 'Baik',
                        'qc_note' => 'Ketebalan terverifikasi mikrometer 2.02mm'
                    ],
                    [
                        'sku' => 'SKU-RAW-002',
                        'name' => 'Stainless Steel Rod SUS304 Dia 25mm',
                        'qty_ordered' => 20,
                        'qty_received' => 20,
                        'qty_rejected' => 0,
                        'uom' => 'Batang',
                        'unit_price' => 275000,
                        'subtotal' => 5500000,
                        'bin' => 'Rak A1-02',
                        'condition' => 'Baik',
                        'qc_note' => 'Diameter toleransi presisi'
                    ]
                ]
            ],
            [
                'id' => 'GR-2026-003',
                'po_number' => 'PO-2026-105',
                'delivery_order_no' => 'SJ-GST-5421',
                'date' => '2026-11-11 13:30',
                'supplier_id' => 'SPL-02',
                'supplier_name' => 'PT Global Semiconductor Tech',
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-01 / B2-02',
                'receiver' => 'Joko Prasetyo',
                'qc_inspector' => 'Hendra Wijaya',
                'qc_status' => 'Passed QC',
                'status' => 'COMPLETED',
                'status_label' => 'Selesai & Masuk Stok',
                'status_badge' => 'success',
                'notes' => 'Komponen elektronik presisi dikemas moisture barrier bag anti-statis.',
                'total_qty' => 70,
                'total_valuation' => 10250000,
                'items' => [
                    [
                        'sku' => 'SKU-ELK-003',
                        'name' => 'Power Supply 24V 10A Din-Rail Mounting',
                        'qty_ordered' => 20,
                        'qty_received' => 20,
                        'qty_rejected' => 0,
                        'uom' => 'Unit',
                        'unit_price' => 245000,
                        'subtotal' => 4900000,
                        'bin' => 'Rak B2-01',
                        'condition' => 'Baik',
                        'qc_note' => 'Output tegangan stabil 24.1V'
                    ],
                    [
                        'sku' => 'SKU-ELK-001',
                        'name' => 'Microcontroller Board ESP32 WiFi+BLE',
                        'qty_ordered' => 50,
                        'qty_received' => 50,
                        'qty_rejected' => 0,
                        'uom' => 'Pcs',
                        'unit_price' => 107000,
                        'subtotal' => 5350000,
                        'bin' => 'Rak B2-02',
                        'condition' => 'Baik',
                        'qc_note' => 'Chipset flash lolos uji boot'
                    ]
                ]
            ],
            [
                'id' => 'GR-2026-005',
                'po_number' => 'PO-2026-112',
                'delivery_order_no' => 'SJ-KBI-9012',
                'date' => '2026-11-12 16:45',
                'supplier_id' => 'SPL-05',
                'supplier_name' => 'PT Krakatau Baja Industri',
                'warehouse_id' => 'GDG-02',
                'warehouse_name' => 'Gudang Transit & Staging',
                'bin_location' => 'Rak T1-01 / T1-02',
                'receiver' => 'Budi Santoso',
                'qc_inspector' => 'Hendra Wijaya',
                'qc_status' => 'Passed QC',
                'status' => 'INSPECTED',
                'status_label' => 'Inspeksi Selesai (Menunggu Putaway)',
                'status_badge' => 'primary',
                'notes' => 'Telah lolos uji uji tarik dan sertifikat material valid, menunggu penempatan rak utama.',
                'total_qty' => 75,
                'total_valuation' => 15600000,
                'items' => [
                    [
                        'sku' => 'SKU-RAW-005',
                        'name' => 'Kawat Las Kuningan 2.5mm Rod AWS',
                        'qty_ordered' => 60,
                        'qty_received' => 60,
                        'qty_rejected' => 0,
                        'uom' => 'Kg',
                        'unit_price' => 120000,
                        'subtotal' => 7200000,
                        'bin' => 'Rak T1-01',
                        'condition' => 'Baik',
                        'qc_note' => 'Berat net sesuai timbangan digital'
                    ],
                    [
                        'sku' => 'SKU-RAW-003',
                        'name' => 'Plat Baja Karbon Hitam 3.0mm',
                        'qty_ordered' => 15,
                        'qty_received' => 15,
                        'qty_rejected' => 0,
                        'uom' => 'Lembar',
                        'unit_price' => 560000,
                        'subtotal' => 8400000,
                        'bin' => 'Rak T1-02',
                        'condition' => 'Baik',
                        'qc_note' => 'Tanpa goresan dan bebas karat'
                    ]
                ]
            ],
            [
                'id' => 'GR-2026-006',
                'po_number' => 'PO-2026-114',
                'delivery_order_no' => 'SJ-MPM-7714',
                'date' => '2026-11-12 18:00',
                'supplier_id' => 'SPL-03',
                'supplier_name' => 'CV Multi Pack Mandiri',
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak C3-01 / C3-02',
                'receiver' => 'Joko Prasetyo',
                'qc_inspector' => 'Sedang Diproses',
                'qc_status' => 'Sedang Uji Sampel',
                'status' => 'RECEIVED',
                'status_label' => 'Diterima (Proses QC)',
                'status_badge' => 'warning',
                'notes' => 'Barang diturunkan di staging bay C3, sedang diambil sampling untuk uji kekuatan karton.',
                'total_qty' => 300,
                'total_valuation' => 4500000,
                'items' => [
                    [
                        'sku' => 'SKU-PKG-001',
                        'name' => 'Karton Box Corrugated 40x30x25cm Double Wall',
                        'qty_ordered' => 200,
                        'qty_received' => 200,
                        'qty_rejected' => 0,
                        'uom' => 'Pcs',
                        'unit_price' => 15000,
                        'subtotal' => 3000000,
                        'bin' => 'Rak C3-01',
                        'condition' => 'Sampling QC',
                        'qc_note' => '10 bundel diperiksa ketebalan flute B/C'
                    ],
                    [
                        'sku' => 'SKU-PKG-002',
                        'name' => 'Lakban Coklat Heavy Duty 48mm x 90m',
                        'qty_ordered' => 100,
                        'qty_received' => 100,
                        'qty_rejected' => 0,
                        'uom' => 'Roll',
                        'unit_price' => 15000,
                        'subtotal' => 1500000,
                        'bin' => 'Rak C3-02',
                        'condition' => 'Sampling QC',
                        'qc_note' => 'Uji tensile tape normal'
                    ]
                ]
            ],
            [
                'id' => 'GR-2026-002',
                'po_number' => 'PO-2026-099',
                'delivery_order_no' => 'SJ-DSE-3319',
                'date' => '2026-11-09 14:15',
                'supplier_id' => 'SPL-04',
                'supplier_name' => 'PT Delta Safety Equipment',
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak D4-01',
                'receiver' => 'Joko Prasetyo',
                'qc_inspector' => 'Hendra Wijaya',
                'qc_status' => 'Passed QC',
                'status' => 'COMPLETED',
                'status_label' => 'Selesai & Masuk Stok',
                'status_badge' => 'success',
                'notes' => 'Perlengkapan K3 batch standard operasional Q4 telah didistribusikan ke rak.',
                'total_qty' => 50,
                'total_valuation' => 4750000,
                'items' => [
                    [
                        'sku' => 'SKU-SFT-001',
                        'name' => 'Helm Proyek MSA V-Gard SNI Putih',
                        'qty_ordered' => 50,
                        'qty_received' => 50,
                        'qty_rejected' => 0,
                        'uom' => 'Pcs',
                        'unit_price' => 95000,
                        'subtotal' => 4750000,
                        'bin' => 'Rak D4-01',
                        'condition' => 'Baik',
                        'qc_note' => 'Logo sertifikasi SNI dan ANSI Z89.1 lengkap'
                    ]
                ]
            ],
            [
                'id' => 'GR-2026-007',
                'po_number' => 'PO-2026-115',
                'delivery_order_no' => 'SJ-GST-5509',
                'date' => '2026-11-12 18:30',
                'supplier_id' => 'SPL-02',
                'supplier_name' => 'PT Global Semiconductor Tech',
                'warehouse_id' => 'GDG-01',
                'warehouse_name' => 'Gudang Utama Logistik',
                'bin_location' => 'Rak B2-01',
                'receiver' => 'Belum Diterima',
                'qc_inspector' => '-',
                'qc_status' => 'Menunggu Fisik',
                'status' => 'DRAFT',
                'status_label' => 'Draft (Menunggu Kedatangan Truk)',
                'status_badge' => 'secondary',
                'notes' => 'Manifest ASN (Advance Shipping Notice) telah diterima dari supplier via EDI/Email.',
                'total_qty' => 200,
                'total_valuation' => 5600000,
                'items' => [
                    [
                        'sku' => 'SKU-ELK-005',
                        'name' => 'Kabel Control Shielded 4x0.75mm',
                        'qty_ordered' => 200,
                        'qty_received' => 0,
                        'qty_rejected' => 0,
                        'uom' => 'Meter',
                        'unit_price' => 28000,
                        'subtotal' => 5600000,
                        'bin' => 'Rak B2-01',
                        'condition' => 'Pending',
                        'qc_note' => 'Armada vendor dijadwalkan tiba malam ini'
                    ]
                ]
            ]
        ];
    }
}
