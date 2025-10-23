<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Enums\UserRole;

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
        Vite::prefetch(concurrency: 3);

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // View app services (Admin + Technician)
        Gate::define('viewAppServices', function (User $user) {
            return in_array($user->role, [
                UserRole::Admin,
                UserRole::Technician,
            ]);
        });

        // Manage app services (Admin only)
        Gate::define('manageAppServices', function (User $user) {
            return $user->role === UserRole::Admin;
        });

        // Assign technician (Admin + BackOffice)
        Gate::define('assignTechnician', function (User $user) {
            return $user->isBackOffice();
        });
    }
}
