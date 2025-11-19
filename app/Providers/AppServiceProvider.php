<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\ApplicationCriterion;
use App\Models\Licence;
use App\Models\LicenceRequirement;
use App\Observers\ApplicationCriterionObserver;
use App\Observers\ApplicationObserver;
use App\Observers\LicenceObserver;
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
        Application::observe(ApplicationObserver::class);
        ApplicationCriterion::observe(ApplicationCriterionObserver::class);
        Licence::observe(LicenceObserver::class);
        LicenceRequirement::observe(LicenceRequirementObserver::class);
    }
}
