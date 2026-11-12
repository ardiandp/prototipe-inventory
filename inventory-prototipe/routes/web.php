<?php

use Illuminate\Support\Facades\Route;
use App\Support\RoleManager;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
});

// Role Switcher Route
Route::get('/switch-role/{role}', function (string $role) {
    if (array_key_exists($role, RoleManager::ROLES)) {
        session(['active_role' => $role]);
    }
    $targetUrl = request()->headers->get('referer') ?: url('/dashboard');
    return redirect($targetUrl)->with('role_switched', RoleManager::get($role)['name']);
});
