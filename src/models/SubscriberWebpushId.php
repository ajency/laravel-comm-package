<?php
namespace Ajency\Comm\Models;

use Ajency\Comm\Providers\Pushcrew;
use Illuminate\Database\Eloquent\Model;

class Subscriber_Webpush_Id extends Model
{
    protected $table = 'aj_comm_subscriber_webpush_ids'; //can make this a config?
    public $incrementing = false;

    public function send($notification)
    {
        switch ($notification['provider']) {
            case 'pushcrew':
                $pushcrew = new Pushcrew();
                $pushcrew->sendNotification($notification);
                break;
            case 'clevertap':
                //TODO
                break;
            case 'fcm':
                //TODO
                break;
        }
    }
}
