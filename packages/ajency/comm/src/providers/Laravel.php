<?php
namespace Ajency\Comm\Providers;

use Ajency\Comm\Models\Log;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Laravel {

    function sendNotification($notification) {

        //Log it - TODD
  /*      $log = new Log();
        $log->setApi('Laravel Mail'); //can get config from config() here - TODO
        $log->setRequest(serialize([]));
        $log->setNotificationData(serialize($notification));

  */      $email_id = $notification['email_id'];

        Mail::send($notification['template_id'], $notification['provider_params'], function ($m) use ($email_id) {
            $m->to($email_id)->subject('Welcome to the jungle!');
        });
/*        $log->setUserId(1);
        $log->setResponse(serialize([]));
        $log->save();*/
    }
}