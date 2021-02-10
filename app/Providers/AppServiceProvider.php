<?php

namespace App\Providers;

use ElasticScoutDriverPlus\Paginator;
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
        Paginator::currentPathResolver(function () {
            return request()->fullUrlWithQuery(['page' => null]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
