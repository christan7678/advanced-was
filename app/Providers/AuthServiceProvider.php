<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user = null, $ability) {
            if (auth('admin')->check()) {
                return true;
            }
        });

        Gate::define('isAdmin', function ($user = null) {
            return auth()->guard('admin')->check();
        });

        Gate::define('isUser', function ($user) {
            return auth()->guard('web')->check();
        });
    }
}
