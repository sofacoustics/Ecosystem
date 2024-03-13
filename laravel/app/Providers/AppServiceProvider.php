<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Datafile;
use App\Observers\DatafileObserver;

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
        Datafile::observe(DatafileObserver::class);
    }
}
