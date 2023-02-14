<?php

namespace App\Providers;

use App\Models\Partner;
use App\Models\Subpartner;
use App\Observers\PartnerObserver;
use App\Observers\SubpartnerObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Partner::observe(PartnerObserver::class);
        Subpartner::observe(SubpartnerObserver::class);
    }
}
