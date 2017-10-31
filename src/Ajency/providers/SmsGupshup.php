<?php
namespace Ajency\Comm\Providers;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Log;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/*
 * SmsGupshup provider class
 */
class SmsGupshup
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
            $log->setApi('SmsGupshup SMS'); //can get config from config() here - TODO
            $log->setRequest(serialize([]));
            $log->setNotificationData(serialize($notification));
            // dd($notification);
            $sms = $notification['sms_id'];
            $to_array = $sms->getTo();
            $response = [];
            foreach ($to_array as $to) {
                $link = 'http://enterprise.smsgupshup.com/GatewayAPI/rest?';
                $array = [
                    'method' => 'SendMessage',
                    'send_to'=> $to,
                    'msg' => urlencode($sms->getMessage()),
                    'userid' => $notification['provider_params']['username'],
                    'auth_scheme' => 'plain',
                    'password' => $notification['provider_params']['password'],
                    'v' => '1.1',
                    'format'=>'text',
                ];
                if($sms->getOverride()) $array['override_dnd'] = 'true';
                $link .= http_build_query($array);
                $ch = curl_init($link);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response[$to] = ['response' => curl_exec($ch), 'request' => $link ];
                curl_close($ch);
                if(explode(' ',$response[$to]['response'])[0] == 'error') throw new Exception($response[$to]['response']);
            }
            
            $log->setUserId(Auth::id());
            $log->setResponse(serialize([$response]));
            $log->save();

        } catch (\Exception $e) {

            $err = new Error();
            $err->setUserId(Auth::id());
            $err->setLevel(3);
            $err->setMessage($e->getMessage());
            $err->setTag('sms-gupshup');
            $err->save();

        }
    }
}
