<?php

use Illuminate\Support\Facades\Route;
use App\Support\RoleManager;
use App\Support\MockDataStore;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $stats = MockDataStore::getSummaryStats();
    $chartData = MockDataStore::getChartData();
    $recentTransactions = array_slice(MockDataStore::getTransactions(), 0, 6);
    $lowStockItems = array_values(array_filter(MockDataStore::getItems(), function ($item) {
        return $item['current_stock'] <= $item['min_stock'];
    }));
    $warehouses = MockDataStore::getWarehouses();

    return view('dashboard.index', compact('stats', 'chartData', 'recentTransactions', 'lowStockItems', 'warehouses'));
});

// Master Data: Barang & SKU
Route::get('/master/barang', function () {
    $items = MockDataStore::getItems();
    $categories = MockDataStore::getCategories();
    $warehouses = MockDataStore::getWarehouses();

    return view('master.barang.index', compact('items', 'categories', 'warehouses'));
});

// Master Data: Lokasi Gudang & Rak
Route::get('/master/lokasi', function () {
    $warehouses = MockDataStore::getWarehouses();
    $items = MockDataStore::getItems();

    return view('master.lokasi.index', compact('warehouses', 'items'));
});

// Master Data: Vendor & Supplier
Route::get('/master/supplier', function () {
    $suppliers = MockDataStore::getSuppliers();

    return view('master.supplier.index', compact('suppliers'));
});

// Operasional: Penerimaan Barang (Inbound Goods Receipt)
Route::get('/operasional/inbound', function () {
    $receipts = MockDataStore::getInboundReceipts();
    $suppliers = MockDataStore::getSuppliers();
    $warehouses = MockDataStore::getWarehouses();
    $items = MockDataStore::getItems();

    return view('operasional.inbound.index', compact('receipts', 'suppliers', 'warehouses', 'items'));
});

// Operasional: Pengeluaran & Transfer Stok (Outbound & Stock Transfer)
Route::get('/operasional/outbound', function () {
    $outboundOrders = MockDataStore::getOutboundOrders();
    $stockTransfers = MockDataStore::getStockTransfers();
    $warehouses = MockDataStore::getWarehouses();
    $items = MockDataStore::getItems();

    return view('operasional.outbound.index', compact('outboundOrders', 'stockTransfers', 'warehouses', 'items'));
});

// Operasional: Stock Opname & Penyesuaian Selisih (Stock Take & Variance)
Route::get('/operasional/opname', function () {
    $sessions = MockDataStore::getOpnameSessions();
    $warehouses = MockDataStore::getWarehouses();
    $items = MockDataStore::getItems();

    return view('operasional.opname.index', compact('sessions', 'warehouses', 'items'));
});

// Laporan: Kartu Stok Digital (Stock Card per SKU)
Route::get('/laporan/kartu-stok', function () {
    $selectedSku = request('sku');
    $items = MockDataStore::getItems();
    $card = MockDataStore::getStockCard($selectedSku);
    $warehouses = MockDataStore::getWarehouses();

    return view('laporan.kartu_stok.index', compact('card', 'items', 'warehouses'));
});

// Laporan: Rekapitulasi Mutasi & Valuasi Persediaan
Route::get('/laporan/mutasi', function () {
    $report = MockDataStore::getInventoryMovementReport();
    $categories = MockDataStore::getCategories();
    $warehouses = MockDataStore::getWarehouses();

    return view('laporan.mutasi.index', compact('report', 'categories', 'warehouses'));
});

// Role Switcher Route
Route::get('/switch-role/{role}', function (string $role) {
    if (array_key_exists($role, RoleManager::ROLES)) {
        session(['active_role' => $role]);
    }
    $targetUrl = request()->headers->get('referer') ?: url('/dashboard');
    return redirect($targetUrl)->with('role_switched', RoleManager::get($role)['name']);
});
