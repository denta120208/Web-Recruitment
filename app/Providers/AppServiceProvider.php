<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ApplyJob;
use App\Models\JobVacancy;
use App\Observers\ApplyJobObserver;
use App\Observers\JobVacancyObserver;

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
    }
}
