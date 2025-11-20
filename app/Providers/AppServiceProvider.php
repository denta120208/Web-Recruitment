<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\ApplyJob;
use App\Models\JobVacancy;
use App\Observers\ApplyJobObserver;
use App\Observers\JobVacancyObserver;
use App\Auth\AdminUserProvider;

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
        // Register observers for HRIS integration
        ApplyJob::observe(ApplyJobObserver::class);
        JobVacancy::observe(JobVacancyObserver::class);

        // Register custom admin user provider
        Auth::provider('admin_eloquent', function ($app, $config) {
            return new AdminUserProvider($app['hash'], $config['model']);
        });
    }
}
