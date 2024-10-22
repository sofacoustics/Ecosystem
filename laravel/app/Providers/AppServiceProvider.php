<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
/*
 * This *was* necessary to get livewire to upload files. However, it turns
 * out that simply setting the X-Forwarded-Proto header to https fixes *everything*!
 use Illuminate\Support\Facades\URL;
 */
use Illuminate\Database\Eloquent\Model;

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

        //jw:note throw excption if attemptinng to fill and unfillable attribute (https://laravel.com/docs/11.x/eloquent#mass-assignment-json-columns) for local development (production should still ignore silently).
        Model::preventSilentlyDiscardingAttributes($this->app->isLocal());
	/*
	 * This *was* necessary to get livewire to upload files. However, it turns
	 * out that simply setting the X-Forwarded-Proto header to https fixes *everything*!
	# https://stackoverflow.com/questions/29912997/laravel-routes-behind-reverse-proxy
        $proxy_scheme = getenv('PROXY_SCHEME');
        if(!empty($proxy_scheme)) {
            URL::forceScheme($proxy_scheme);
	}
	 */
    }
}
