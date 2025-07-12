<?php

namespace App\Providers;


use Illuminate\Support\Facades\Gate; // <-- تأكد من وجود هذا السطر
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
         // هذا السطر يمنح صلاحية كاملة للدور المسمى 'Super Admin'
        // سيتجاوز هذا الدور كل عمليات التحقق من الصلاحيات الأخرى
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
