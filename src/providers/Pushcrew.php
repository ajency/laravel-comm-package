<?php
namespace Ajency\Comm\Providers;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Log;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class Pushcrew {

    private $url = 'https://pushcrew.com/api/v1/send/individual';

    function sendNotification($notification) {

        //Log it - TODD

        try {
            $log = new Log();

            $post = [
                "title" => $notification['provider_params']['title'],
                "message" => $notification['provider_params']['message'],
                "url" => $notification['provider_params']['url'],
                "image_url" => $notification['provider_params']['image_url'],
                "subscriber_id" => $notification['subscriber_id']
            ];

            $log->setRequest(serialize($post));
            $log->setNotificationData(serialize($notification));
            $log->setApi($this->url);
            $log->setUserId(Auth::id());
            $ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: key=f694d03321eca313a4030b091b942ec4'
            ));
            $response = curl_exec($ch);
            curl_close($ch);
            $log->setResponse(serialize($response));
            $log->save();

        } catch (\Exception $e) {
            $err = new Error();
            $err->setUserId(Auth::id());
            $err->setLevel(3);
            $err->setMessage($e->getMessage());
            $err->setTag('pushcrew');
            $err->save();
        }
    }
}