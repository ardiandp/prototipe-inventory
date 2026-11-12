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

// Role Switcher Route
Route::get('/switch-role/{role}', function (string $role) {
    if (array_key_exists($role, RoleManager::ROLES)) {
        session(['active_role' => $role]);
    }
    $targetUrl = request()->headers->get('referer') ?: url('/dashboard');
    return redirect($targetUrl)->with('role_switched', RoleManager::get($role)['name']);
});
