<?php

namespace Ajency\Comm;

use Illuminate\Support\ServiceProvider;

class CommServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');

    }
}
