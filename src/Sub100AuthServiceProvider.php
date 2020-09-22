<?php


namespace sub100\Auth;


use Illuminate\Support\ServiceProvider;

class Sub100AuthServiceProvider extends ServiceProvider
{

    public function boot()
    {

        $this->publishes([
            __DIR__ . '/../config/sub100.php' => config_path('sub100.php'),
        ], 'sub100-config');

        $this->app->singleton('Sub100Auth', function ($app) {
            return new sub100\Auth\Authentication();
        });
    }

    public function register()
    {

    }

}
