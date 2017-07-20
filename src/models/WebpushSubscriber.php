<?php
namespace Ajency\Comm\Models;

use Ajency\Comm\Providers\Pushcrew;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


/*
 * Class exposed to allow developer to create an Web push Subscriber and pass to add subscription method
 */
class WebpushSubscriber extends Model
{
    protected $table = 'aj_comm_webpush_ids'; //can make this a config? - TODO

    protected $attributes = [
        'ref_type' => 'User'
    ];

    /**
     * @param mixed $ref_id
     */
    public function setRefId($ref_id)
    {
        $this->attributes['ref_id'] = $ref_id;
    }

    public function setProvider($provider)
    {
        $this->attributes['provider'] = $provider;
    }

    public function setSubscriberId($subscriber_id)
    {
        $this->attributes['subscriber_id'] = $subscriber_id;
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
