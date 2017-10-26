<?php
namespace Ajency\Comm\Providers;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Log;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/*
 * Laravel provider class
 */
class Laravel
{
    /*
     * Method that send the email based on project env config for emails
     *
     * @param array $notification
     *
     * No retun as errors are logged using error class
     */
    public function sendNotification($notification)
    {
        try {

            $log = new Log();
            $log->setApi('Laravel Mail'); //can get config from config() here - TODO
            $log->setRequest(serialize([]));
            $log->setNotificationData(serialize($notification));
            $email_id = $notification['email_id'];
            $subject = $email_id->getParams()['email_subject'];
            Mail::send($notification['template_id'], $email_id->getParams(), function ($m) use ($email_id, $subject) {
                $from = $email_id->getFrom();
                $m->from($from['address'],$from['name']);

                $m->to($email_id->getTo());
                $m->cc($email_id->getCc());
                $m->bcc($email_id->getBcc());

                $m->subject($subject);
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
