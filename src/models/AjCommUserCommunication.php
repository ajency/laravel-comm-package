<?php

namespace Ajency\Comm\Models;

use Ajency\Comm\Providers\Laravel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AjCommUserCommunication extends Model
{
    protected $table    = 'user_communications';
    protected $fillable = [
        'object_id', 'object_type', 'type', 'value', 'country_code', 'is_primary', 'is_communication', 'is_verified', 'is_visible',
    ];

    protected $attributes = [
        'is_communication' => 1,
        'country_code'     => 0,
        'is_verified'      => 1,
        'is_visible'       => 1,
        'is_primary'       => 1,
    ];

    public function getattributes()
    {
        return $this->attributes;
    }

    public function setAttributes($params)
    {
        $subscriber_attributes = array('object_id', 'object_type', 'type', 'value', 'country_code', 'is_primary', 'is_communication', 'is_verified', 'is_visible');
        if (!isset($params['type'])) {
            return false;
        }

        foreach ($params as $param_key => $param_value) {

            if (in_array($param_key, $subscriber_attributes)) {

                $this->attributes[$param_key] = $param_value;

            }

        }

        if (!$this->attributes['object_id']) {
            $this->attributes['object_id'] = Auth::id();
        }

    }

    /*
     * Method to split between providers
     */
    public function sendEmails($notification)
    {
        // dd($notification);
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
