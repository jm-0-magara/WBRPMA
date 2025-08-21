<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        /*
        // FOR CLOUDFARED TEMPORARY LINKS
        if (App::runningInConsole() === false) {
            if (request()->isSecure()) {
                URL::forceScheme('https');
            }

            config(['app.url' => request()->getSchemeAndHttpHost()]);
        }

        //I ALSO CHANGED .env APP_URL FROM https://cfd9-41-90-65-89.ngrok-free.app 
        // AND ADDED OTHER .env VARIABLES (2) BELOW IT

        //THIS WORKED:
        APP_URL=https://beverage-chest-camera-discharge.trycloudflare.com  
        ASSET_URL=https://beverage-chest-camera-discharge.trycloudflare.com  
        SESSION_DOMAIN=698e1feb-f5df-4ff2-bb8e-893db1e946e8
        SESSION_SECURE_COOKIE=true
        */

        // if (env('APP_ENV') === 'local') {
          //  URL::forceScheme('https');
        //}
    }
}
