<?php

namespace Ajency\Comm;

use Ajency\Comm\Communication;
use Ajency\Comm\Models\Log;
use Illuminate\Support\Facades\Auth;
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
            __DIR__ . '/config/aj-comm-channels.php' => config_path('aj-comm-channels.php'),
            __DIR__ . '/config/aj-comm-events.php'   => config_path('aj-comm-events.php'),
        ]);
    }

    public static function sendNotification(Communication\Notification $notification)
    {
        $comm = new Communication\Communication();
        $comm->setNotifications($notification);
        return $comm->beginCommunication();
    }

    public static function createSubscriptions($communication_details)
    {
        $log = new Log();
        $log->setApi('Create Subscription'); //can get config from config() here - TODO
        $log->setRequest(serialize([]));

        foreach ($communication_details as $communication_detail) {
            $result = $communication_detail->createSubscriptions();
        }

        $log->setNotificationData(serialize($result));
        $log->setUserId(Auth::id());
        $log->setResponse(serialize([]));
        $log->save();
        return $result;
        /*$sub = new Subscription\Subscription();
    return $sub->createSubscription($communication_details);*/
    }

    public static function processNotificationJob($notificationJob)
    {
        $send = new Communication\Dispatch();
        $send->setNotificationJob($notificationJob);
        return $send->processNotification();
    }
}
