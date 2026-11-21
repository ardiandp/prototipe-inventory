<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Support\RoleManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('currentRole', RoleManager::getCurrentRole());
            $view->with('allRoles', RoleManager::all());
            $view->with('inventoryStats', \App\Support\MockDataStore::getSummaryStats());
        });
    }
}
