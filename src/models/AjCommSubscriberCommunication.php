<?php

namespace Ajency\Comm\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class to add subscriber of type (email,mobile,web-push). and pass to add subscription method
 */
class AjCommSubscriberCommunication extends Model
{

    protected $attributes = ['is_primary' => 0];

    /**
     * @param      <type>  $args   The arguments
     */
    public function setAttributes($params)
    {
        $subscriber_attributes = array('ref_id', 'ref_type', 'value', 'is_primary', 'service', 'country_code');
        if (!isset($params['service'])) {
            return false;
        }

        $cnt = 1;

        foreach ($params as $param_key => $param_value) {
            /*echo "<br/>". $cnt.") ".$param_key." - ".$param_value;

            var_dump(in_array($param_key,$subscriber_attributes));*/
            if (in_array($param_key, $subscriber_attributes)) {
                $this->attributes[$param_key] = $param_value;
            }
            $cnt++;
        }

    }

    /**
     * @return     <type>  The attributes.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function save(array $options = array())
    {
        if (!$this->ref_id) {
            $this->ref_id = Auth::id();
        }
        parent::save($options);
    }

    /*
     * Method to split between providers
     */
    public function sendEmails($notification)
    {
        switch ($notification['provider']) {
            case 'laravel':
                $laravel = new Laravel();
                $laravel->sendNotification($notification);
                break;

            case 'mandrill':
                //TODO
                break;

            case 'sendgrid':
                //TODO
                break;
        }
    }
}
