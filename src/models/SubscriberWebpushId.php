<?php
namespace Ajency\Comm\Models;

use Ajency\Comm\Providers\Pushcrew;
use Illuminate\Database\Eloquent\Model;

class SubscriberWebpushId extends Model
{
    protected $table = 'aj_comm_subscriber_webpush_ids'; //can make this a config? - TODO

    protected $attributes = [
        'ref_type' => 'User'
    ];

    /**
     * @param mixed $ref_id
     */
    public function setRefId($ref_id)
    {
        $this->attributes['ref_id'] = isset($ref_id) ? $ref_id: Auth::id();
    }

    public function setProvider($provider)
    {
        $this->attributes['provider'] = $provider;
    }

    public function setSubscriberId($subscriber_id)
    {
        $this->attributes['subscriber_id'] = $subscriber_id;
    }

    public function sendWebPushes($notification)
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
