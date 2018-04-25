<?php

namespace App\Providers;

use App\Helpers\DmClient;
use App\Helpers\Publications;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DmClient::class, function() {
            return new DmClient(config('services.dmserver.endpoint'), config('services.dmserver.roles_passwords'));
        });
        $this->app->singleton(Publications::class, function() {
            return new Publications(resolve(DmClient::class));
        });
    }
}
