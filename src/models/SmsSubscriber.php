<?php
namespace Ajency\Comm\Models;

use Ajency\Comm\Providers\SmsGupshup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SmsSubscriber extends Model
{
	 protected $table = 'user_communications';  

     protected $attributes = [
        
        'type'       =>'sms'   
    ];

     /*
     * Method to split between providers
     */
    public function sendSms($notification)
    {
        // dd($notification);
        switch ($notification['provider']) {
            case 'smsgupshup':
                $sms = new SmsGupshup();
                $sms->sendNotification($notification);
                break;
        }
    }
}