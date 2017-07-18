<?php

namespace Ajency\Comm;

use Ajency\Comm\Communication;
use Ajency\Comm\Subscription;
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

    public static function sendNotification($event, $recepient_ids)
    {
        $comm = new Communication\Communication();
        $comm->setEvent($event);
        $comm->setRecepientIds($recepient_ids);
        return $comm->beginCommunication();
    }

    public static function createSubscription($communication_details) {

        $sub = new Subscription\Subscription();
        return $sub->createSubscription($communication_details);
    }

    public static function processNotifications($notification) {

        $send = new Communication\Notification();
        $send->setNotification($notification);
        return $send->processNotification();
    }
}
