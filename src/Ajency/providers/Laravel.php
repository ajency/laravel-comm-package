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

            $provider_email_params = $notification['provider_params'];

            $email_id = $notification['email_id'];
            $subject  = $provider_email_params['subject']; //$email_id->getParams()['subject'];

            $from         = $email_id->getFrom();
            $from_address = $from['address'];
            $from_name    = $from['name'];

            /*Get from addresss/name if it is set on email recipient,
            if not get from address/name, if it is  set as provider param,
            if not get from address/name from aj-comm-channels config  "email" => ["provider" => "laravel" , "password" => "" , "username" => "",'from_address'=>'parag+frommail2@ajency.in','from_name'=>'fnbtestname'],
             */
            if ($from['address'] == '') {
                if (isset($provider_email_params['from_address'])) {
                    $from_address = $provider_email_params['from_address'];
                    $from_name    = $provider_email_params['from_name'];
                } else {

                    $from_address = config('aj-comm-channels.email.from_address');
                    $from_name    = config('aj-comm-channels.email.from_name');
                }
            }

            $email_id->setFrom($from_address, $from_name);

            Mail::send($notification['template_id'], $email_id->getParams(), function ($m) use ($email_id, $subject) {

                $from         = $email_id->getFrom();
                $from_address = $from['address'];
                $from_name    = $from['name'];

                $m->from($from_address, $from_name);
                $m->to($email_id->getTo());
                $m->cc($email_id->getCc());
                $m->bcc($email_id->getBcc());
                $m->subject($subject);
                $attachments = $email_id->getAttachments();
                foreach ($attachments as $attach) {
                    if (!isset($attach['file'])) {
                        throw new \Exception("attachment without file", 1);
                    }

                    if (!isset($attach['as']) or $attach['as'] == "") {
                        $attach['as'] = basename($attach['file']);
                    }

                    if (!isset($attach['mime'])) {
                        $attach['mime'] = "";
                    }

                    $m->attachData(base64_decode($attach['file']), $attach['as'], ['mime' => $attach['mime']]);

                }
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
