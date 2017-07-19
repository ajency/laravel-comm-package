<?php

namespace Ajency\Comm;

use Ajency\Comm\Communication;
use Ajency\Comm\Subscription;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class CommServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
       # Schema::defaultStringLength(191);
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->publishes([
            __DIR__.'/config/aj-comm-channels.php' => config_path('aj-comm-channels.php'),
            __DIR__.'/config/aj-comm-events.php' => config_path('aj-comm-events.php'),
        ]);
    }

    public static function sendNotification(Communication\Notification $notification)
    {
        $comm = new Communication\Communication();
        $comm->setNotifications($notification);
        return $comm->beginCommunication();
    }

    public static function createSubscription($communication_details) {

        $sub = new Subscription\Subscription();
        return $sub->createSubscription($communication_details);
    }

    public static function processNotificationJob($notificationJob) {

        $send = new Communication\Dispatch();
        $send->setNotificationJob($notificationJob);
        return $send->processNotification();
    }
}
