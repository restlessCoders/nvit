<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        /*if (env('APP_ENV') === 'production') {
            \URL::forceScheme('https');
        }*/
        //To use Pagination from Bootstrap
        Paginator::useBootstrap();
    }
}
