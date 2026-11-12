<?php

namespace App\Support;

class RoleManager
{
    public const ROLES = [
        'wh_manager' => [
            'id' => 'wh_manager',
            'name' => 'Warehouse Manager',
            'user_name' => 'Budi Santoso',
            'badge' => 'primary',
            'icon' => 'bi-person-badge-fill',
            'avatar' => 'avatar.png',
            'description' => 'Akses penuh seluruh modul pergudangan, persetujuan mutasi & laporan analitik',
            'permissions' => [
                'dashboard',
                'master_barang',
                'master_lokasi',
                'master_supplier',
                'inbound',
                'outbound',
                'opname',
                'kartu_stok',
                'laporan_mutasi',
                'pengaturan'
            ]
        ],
        'wh_staff' => [
            'id' => 'wh_staff',
            'name' => 'Warehouse Staff',
            'user_name' => 'Joko Prasetyo',
            'badge' => 'info',
            'icon' => 'bi-box-seam',
            'avatar' => 'avatar2.png',
            'description' => 'Operasional harian: input penerimaan, pengeluaran & pencatatan opname fisik',
            'permissions' => [
                'dashboard',
                'master_barang',
                'master_lokasi',
                'inbound',
                'outbound',
                'opname'
            ]
        ],
        'purchasing' => [
            'id' => 'purchasing',
            'name' => 'Purchasing / Buyer',
            'user_name' => 'Siti Rahma',
            'badge' => 'warning',
            'icon' => 'bi-cart-check-fill',
            'avatar' => 'avatar3.png',
            'description' => 'Pemantauan stok menipis, data master vendor supplier, & riwayat pembelian',
            'permissions' => [
                'dashboard',
                'master_barang',
                'master_supplier',
                'inbound',
                'kartu_stok'
            ]
        ],
        'finance' => [
            'id' => 'finance',
            'name' => 'Finance / Auditor',
            'user_name' => 'Hendra Wijaya',
            'badge' => 'success',
            'icon' => 'bi-cash-coin',
            'avatar' => 'avatar4.png',
            'description' => 'Audit nilai persediaan, valuasi aset stok, & review selisih opname',
            'permissions' => [
                'dashboard',
                'master_barang',
                'opname',
                'kartu_stok',
                'laporan_mutasi'
            ]
        ],
        'admin' => [
            'id' => 'admin',
            'name' => 'Super Admin',
            'user_name' => 'Administrator',
            'badge' => 'dark',
            'icon' => 'bi-shield-shaded',
            'avatar' => 'avatar5.png',
            'description' => 'Akses kontrol penuh, pengaturan master lokasi gudang & simulasi sistem',
            'permissions' => [
                'dashboard',
                'master_barang',
                'master_lokasi',
                'master_supplier',
                'inbound',
                'outbound',
                'opname',
                'kartu_stok',
                'laporan_mutasi',
                'pengaturan'
            ]
        ]
    ];

    /**
     * Dapatkan daftar seluruh role.
     */
    public static function all(): array
    {
        return self::ROLES;
    }

    /**
     * Dapatkan detail role berdasarkan ID.
     */
    public static function get(string $roleKey): array
    {
        return self::ROLES[$roleKey] ?? self::ROLES['wh_manager'];
    }

    /**
     * Dapatkan role aktif saat ini (dari session/request atau default wh_manager).
     */
    public static function getCurrentRole(): array
    {
        $roleKey = request()->query('role')
            ?? (function_exists('session') ? session('active_role') : null)
            ?? 'wh_manager';

        if (!array_key_exists($roleKey, self::ROLES)) {
            $roleKey = 'wh_manager';
        }

        return self::ROLES[$roleKey];
    }

    /**
     * Periksa apakah role aktif memiliki hak akses permission tertentu.
     */
    public static function can(string $permission, ?string $roleKey = null): bool
    {
        $role = $roleKey ? self::get($roleKey) : self::getCurrentRole();
        return in_array($permission, $role['permissions'], true);
    }
}
