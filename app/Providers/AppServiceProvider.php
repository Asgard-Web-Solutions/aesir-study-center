<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;

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

        Gate::define('isAdmin', function (User $user) {
            return $user->isAdmin;
        });

        Feature::define('flash-cards', function () {
            return true;

            if (app()->environment(['local', 'testing'])) {
                return true;
            }

            if (env('FEATURE_FLAG_FLASH_CARDS')) {
                return true;
            }

            return false;
        });

        Feature::define('profile-test-manager', function () {
            return true;

            if (app()->environment(['local', 'testing'])) {
                return true;
            }

            if (env('FEATURE_FLAG_PROFILE_TEST_MANAGER')) {
                return true;
            }

            return false;
        });

        Feature::define('mage-upgrade', function () {
            if (app()->environment(['local', 'testing'])) {
                return true;
            }

            if (env('FEATURE_FLAG_MAGE_UPGRADE')) {
                return true;
            }

            return false;
        });

    }
}
