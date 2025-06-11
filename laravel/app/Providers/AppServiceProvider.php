<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
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

        // Increase rate limit to avoid "429 Too Many Requests" error
        //jw:note https://onlinecode.org/fixing-429-too-many-requests-in-laravel-11/
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(5000);
		});

		// global variables available in all blades
		View::share('buttonColorEnabled', 'blue-500 bg-blue-500 hover:bg-blue-700 text-white');
		View::share('buttonColorDisabled', 'gray-400 bg-gray-400 text-white');
		View::share('buttonColorDelete', 'red-400 bg-red-400 hover:bg-red-600 text-white');
		View::share('buttonStyle', 'font-bold mx-1 my-1 py-1 px-2 rounded');

        //$this->app->useStoragePath(config('app.app_storage_path')); //jw:note this could be used to put all files including cache on external disk in conjunctino with app.php

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
