<?php

namespace App\Providers;

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
