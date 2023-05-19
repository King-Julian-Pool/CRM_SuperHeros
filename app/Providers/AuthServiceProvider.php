<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('access-hero', function ($user = null) {
            return $user->role_id === User::ROLE_HERO || $user->role_id === User::ROLE_ADMIN;
        });

        Gate::define('access-commune', function ($user = null) {
            return $user->role_id === User::ROLE_COMMUNE || $user->role_id === User::ROLE_ADMIN;
        });

        Blade::if('hero', function () {
            return auth()->user()->role_id === User::ROLE_HERO;
        });

        Blade::if('commune', function () {
            return auth()->user()->role_id === User::ROLE_COMMUNE;
        });
    }
}
