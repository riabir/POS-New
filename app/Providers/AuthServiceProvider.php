<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Add custom guard
        Auth::extend('employee_status', function ($app, $name, array $config) {
            return new \App\Auth\EmployeeStatusGuard(
                Auth::createUserProvider($config['provider']),
                $app['request']
            );
        });
    }
}