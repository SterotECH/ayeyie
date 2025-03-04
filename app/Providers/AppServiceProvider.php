<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
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
        $this->configureModels();
        $this->configureDates();
        $this->configureUrl();
    }

    /**
     * Configure the application models.
     */
    private function configureModels(): void
    {
        Model::shouldBeStrict();
        Model::unguard();
    }

    /**
     * Configure Application Dates
     */
    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Configure Application URL
     */
    private function configureUrl(): void
    {
        URL::forceHttps($this->app->environment('production'));
    }
}
