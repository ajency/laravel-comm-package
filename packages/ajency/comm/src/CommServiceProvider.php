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
        $this->loadMigrationsFrom( __DIR__ . '/migrations');
    }
}
