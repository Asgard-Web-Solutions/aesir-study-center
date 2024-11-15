<?php

namespace App\Providers;

use App\Models\User;
use Laravel\Pennant\Feature;
use Laravel\Pulse\Facades\Pulse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/profile/exams';

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

        Gate::define('viewPulse', function (User $user) {
            return $user->isAdmin;
        });

        Pulse::user(fn ($user) => [
            'name' => $user->name,
            'extra' => $user->email,
            'avatar' => $user->gravatarUrl(),
        ]);

        Feature::define('flash-cards', function () {
            if (app()->environment(['local', 'testing'])) {
                return true;
            }

            if (env('FEATURE_FLAG_FLASH_CARDS')) {
                return true;
            }

            return false;
        });

        Feature::define('profile-test-manager', function () {
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

            if (env('FEATURE_FLAG_MAGE_UPGRADE') == 'true') {
                return true;
            }

            return false;
        });

        Feature::define('captcha', function () {
            if (app()->environment(['local', 'testing'])) {
                return false;
            }

            if (env('FEATURE_FLAG_CAPTCHA')) {
                return true;
            }

            return false;
        });

        Feature::define('email_verification', function () {
            if (app()->environment(['local', 'testing'])) {
                return false;
            }

            if (env('FEATURE_FLAG_EMAIL_VERIFICATION')) {
                return true;
            }

            return false;
        });

    }
}
