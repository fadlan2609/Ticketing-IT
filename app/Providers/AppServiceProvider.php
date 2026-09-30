<?php

namespace App\Providers;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Blade directive untuk role & permission
        Blade::if('role', function (string $role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('hasanyrole', function (string $roles) {
            return auth()->check() && auth()->user()->hasAnyRole(explode('|', $roles));
        });

        Blade::if('permission', function (string $permission) {
            return auth()->check() && auth()->user()->can($permission);
        });

        // Share company setting ke SEMUA views
        View::composer('*', function ($view) {
            $view->with('company', CompanySetting::instance());
        });
    }
}