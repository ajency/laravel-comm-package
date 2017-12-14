<?php
namespace Ajency\Comm\Providers;

/*
 * PepoCampaign provider class
 */
class PepoCampaign
{
    private $url = '';
    private $pepo_key;
    private $pepo_secret;
 

    public function sendNotification($notification)
    {
        dd($notification);
        try {

            $this->pepo_key    = $notification['provider_params']['pepo_key'];
            $this->pepo_secret = $notification['provider_params']['pepo_secret'];

            $pepo_template = $notification['pepo_template'];

            $email_vars = array();

            $request_time = date("Y-m-d\TH:i:sP");
            $delimiter    = '::';

            $signature_str = $api_url . $delimiter . $request_time;
            $signature     = hash_hmac('sha256', $signature_str, $this->pepo_secret, false);

            $fields = array(
                'request-time' => $request_time,
                'signature'    => $signature,
                'api-key'      => $this->pepo_key,
                'email'        => $email,
                'template'     => $pepo_template,
                'email_vars'   => json_encode($email_vars),
            );

            $fields_string = http_build_query($fields);

            $ch           = curl_init();
            $curl_options = array(
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSLVERSION     => 6,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $fields_string,
            );
            curl_setopt_array($ch, $curl_options);
            $result = curl_exec($ch);
            if ($result === false) {
                //throw new Exception(curl_error($ch), curl_errno($ch));
                $this->setPepoCampaingErrorLog(curl_error($ch));
            } else {
                $log->setUserId(Auth::id());
                $log->setResponse(serialize($result));
                $log->save();
            }

        } catch (\Exception $e) {

            $this->setPepoCampaingErrorLog($e->getMessage());

        }

    }

    public function setPepoCampaingErrorLog($error_msg)
    {

        $err = new Error();
        $err->setUserId(Auth::id());
        $err->setLevel(3);
        $err->setMessage($error_msg);
        $err->setTag('pepocampaign');
        $err->save();

    }

}
