<?php

namespace Ajency\Comm;

use Ajency\Comm\API\Communication;
use Ajency\Comm\Subscription\Subscription;
use Illuminate\Support\ServiceProvider;
use Ajency\Comm\API\Notification;

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
        $comm = new Communication();
        $comm->setEvent($event);
        $comm->setRecepientIds($recepient_ids);
        $comm->beginCommunication();
    }

    public static function createSubscription($user_id, $communication_details) {

        $sub = new Subscription();
        $sub->setUserId($user_id);
        $sub->setMobileNos($communication_details['mobile_nos']);
        $sub->setWebpushIds($communication_details['web_push_ids']);
        $sub->setEmails($communication_details['emails']);
        $sub->createSubscription();

    }

    public static function processNotifications($notification) {

        $send = new Notification();
        $send->setNotification($notification);
        $send->processNotification();
    }
}
