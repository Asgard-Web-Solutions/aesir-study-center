<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Pennant\Feature;
use App\Models\User;


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
            return ($user->isAdmin);
        });

        Feature::define('flash-cards', function() {
            return true;
            
            if (app()->environment(['local', 'testing'])) {
                return true;
            }
    
            if (env('FEATURE_FLAG_FLASH_CARDS')) {
                return true;
            }
    
            return false;
        });

        feature::define('profile-test-manager', function() {
            return true;
            
            if (app()->environment(['local', 'testing'])) {
                return true;
            }
    
            if (env('FEATURE_FLAG_PROFILE_TEST_MANAGER')) {
                return true;
            }
    
            return false;
        });
    }
}
