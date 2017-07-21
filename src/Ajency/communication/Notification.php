<?php
namespace Ajency\Comm\Communication;


use Ajency\Comm\Models\Subscriber_Webpush_Id;
use App\Jobs\processEvents;

use Ajency\Comm\Models\Subscriber_Email;

/*
 * A class which is exposed to package user to create a Notification object, this notification object is passed along with the communication  call
 */
class Notification
{
    /**
     * A variable indicating what is th communication event that has occured
     *
     * @var string
     */
    private $event;

    /**
     * A list of recepient user ids for this notification
     *
     * @var string
     */
    private $recipient_ids;
    /**
     * An associative array of data required by the provider to send out communication
     *
     * @var string
     */
    private $provider_params;

    /**
     * A list of channels to send this notification on eg. email, mobile, web-push
     *
     * @var string
     */
    private $channels;

    /**
     * @return mixed
     */
    public function getChannels()
    {
        return is_array($this->channels) ? $this->channels : [$this->channels];
    }

    /**
     * @param mixed $channels
     */
    public function setChannels($channels)
    {
        $this->channels = $channels;
    }

    /**
     * @return mixed
     */
    public function getProviderParams()
    {
        return $this->provider_params;
    }

    /**
     * @param $provider_params
     */
    public function setProviderParams($provider_params)
    {
        $this->provider_params = $provider_params;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getRecipientIds()
    {
        return is_array($this->recipient_ids) ? $this->recipient_ids : [$this->recipient_ids];
    }

    /**
     * @param mixed $recipient_ids
     */
    public function setRecipientIds($recipient_ids)
    {
        $this->recipient_ids = $recipient_ids;
    }
}
