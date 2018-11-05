<?php
namespace Ajency\Comm\campaigns;

use Ajency\Comm\sdks\campaigns\PepoCampaigns;

/*
 * PepoCampaign provider class
 */
class AjPepoCampaign
{
    private $url = '';
    /* private $pepo_key;
    private $pepo_secret;*/
    private $pepo_config;

    public function __construct()
    {
        $campaign_config = config('ajency.comm.aj-comm-campaign');
        $config          = $campaign_config['pepo'];

        $this->pepo_config['key']    = $config['key'];
        $this->pepo_config['secret'] = $config['secret'];
    }

    /**
     * @param      <type>         $list_id  Peposampaign list identifier
     * @param      <type>         $data     The data array('email'=>'abc@mailinatot.com','first_name'=>'abc','last_name'=>'xyz')
     *
     * @return     PepoCampaigns  ( description_of_the_return_value )
     */
    public function addToList($list_id, $data)
    {

        $pepocampaign_obj = new PepoCampaigns($this->pepo_config);
        $data_default     = array('first_name' => '', 'last_name' => '');
        $data             = array_merge($data_default, $data);

        $result = $pepocampaign_obj->add_contact_to_list($list_id, $data);
        return json_decode($result[0]);

    }

    /**
     * @param      <type>         $list_id  Pepo Campaign list identifier
     * @param      <type>         $data     The data array('email'=>'abc@mailinatot.com')
     *
     * @return     PepoCampaigns  ( description_of_the_return_value )
     */
    public function removeContactFromList($list_id, $data)
    {

        $pepocampaign_obj = new PepoCampaigns($this->pepo_config);
        //$pepocampaign_obj->test();
        $result = $pepocampaign_obj->remove_contact_from_list($list_id, $data);
        return json_decode($result[0]);
    }

    public function getLists()
    {

        $pepocampaign_obj = new PepoCampaigns($this->pepo_config);

        $lists = $pepocampaign_obj->getLists();

        return $lists;

    }

/*

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

}*/

    /* public function setPepoCampaingErrorLog($error_msg)
{

$err = new Error();
$err->setUserId(Auth::id());
$err->setLevel(3);
$err->setMessage($error_msg);
$err->setTag('pepocampaign');
$err->save();

}*/

}