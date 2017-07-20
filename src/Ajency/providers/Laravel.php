<?php
namespace Ajency\Comm\Providers;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Log;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/*
 * Laravel provider class
 */
class Laravel
{
    public function sendNotification($notification)
    {
        try {
            $log = new Log();
            $log->setApi('Laravel Mail'); //can get config from config() here - TODO
            $log->setRequest(serialize([]));
            $log->setNotificationData(serialize($notification));
            $email_id = $notification['email_id'];
            $subject = $notification['provider_params']['subject'];
            Mail::send($notification['template_id'], $notification['provider_params'], function ($m) use ($email_id, $subject) {
                $m->to($email_id)->subject($subject);
            });
            $log->setUserId(Auth::id());
            $log->setResponse(serialize([]));
            $log->save();
        } catch (\Exception $e) {
            $err = new Error();
            $err->setUserId(Auth::id());
            $err->setLevel(3);
            $err->setMessage($e->getMessage());
            $err->setTag('laravel-mail');
            $err->save();
        }
    }
}
