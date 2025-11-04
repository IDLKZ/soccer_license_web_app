<?php

namespace App\Providers;

use App\Models\ApplicationCriterion;
use App\Models\LicenceRequirement;
use App\Observers\ApplicationCriterionObserver;
use App\Observers\LicenceRequirementObserver;
use Illuminate\Support\ServiceProvider;

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
        ApplicationCriterion::observe(ApplicationCriterionObserver::class);
        LicenceRequirement::observe(LicenceRequirementObserver::class);
    }
}
