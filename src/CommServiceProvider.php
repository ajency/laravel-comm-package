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
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->publishes([
            __DIR__.'/config/aj-comm-channels.php' => config_path('aj-comm-channels.php'),
            __DIR__.'/config/aj-comm-events.php' => config_path('aj-comm-events.php'),
        ]);
    }
}
