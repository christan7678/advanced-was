<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Booking;
use App\Policies\BookingPolicy;

use App\Models\Ticket;
use App\Policies\TicketPolicy;

use App\Models\User;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Booking::class => BookingPolicy::class,
        Ticket::class => TicketPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

         // Only super admin can bypass all authorization checks
        Gate::before(function ($user, $ability) {
            if ($user->role === 'super_admin') {
                return true;
            }
        });

        Gate::define('isSuperAdmin', function ($user) {
            return $user->role === 'super_admin';
        });

        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('administration', function ($user) {
            return in_array($user->role, ['admin', 'super_admin']);
        });


        Gate::define('isUser', function ($user) {
            return $user->role === 'user';
        });

        Gate::define('manage-admins', function ($user) {
            return $user->role === 'super_admin';
        });

        
    }

    
}
