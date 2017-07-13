<?php
namespace Ajency\Comm\API;

use App\Jobs\processEvents;
use App\Jobs\sendNotification;

class Notification {

    public static function send_notification($event, $user_ids){
        $provider = new Provider();
        $jobs = $provider->getProvidersForEvent($event,$user_ids);
        //TODO log jobs here
        foreach($jobs as $job) {
            dispatch(new processEvents($job));
        }
    }
}