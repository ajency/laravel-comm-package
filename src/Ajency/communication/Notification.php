<?php
namespace Ajency\Comm\Communication;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Subscriber_Webpush_Id;
use App\Jobs\processEvents;
use Illuminate\Support\Facades\Auth;
use Ajency\Comm\Models\Subscriber_Email;


/*
 * A base class that lets us define Communication methods
 * Communication methods are any methods utilized to send notification via queue process
 */
class Notification
{
    private $event;

    private $recepient_ids;

    private $provider_params;

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
     * @param mixed $template_vars
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
    public function getRecepientIds()
    {
        return is_array($this->recepient_ids) ? $this->recepient_ids : [$this->recepient_ids];
    }

    /**
     * @param mixed $recepient_ids
     */
    public function setRecepientIds($recepient_ids)
    {
        $this->recepient_ids = $recepient_ids;
    }
}