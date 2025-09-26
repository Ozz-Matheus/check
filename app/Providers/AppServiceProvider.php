<?php

namespace App\Providers;

use App\Models\AuditItem;
use App\Models\RiskControl;
use App\Observers\AuditItemObserver;
use App\Observers\RiskControlObserver;
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
        //
        RiskControl::observe(RiskControlObserver::class);
        AuditItem::observe(AuditItemObserver::class);
    }
}
