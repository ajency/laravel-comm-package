<?php
namespace Ajency\Comm\Communication;

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
     * Delay in minutes for a dispatched job
     *
     * @var integer
     */
    private $delay = 0;

     /**
     * Priority for the dispatched notification
     *
     * @var string
     */
    private $priority = 'default';

    /**
     * @return mixed
     */
    public function getChannels()
    {
        $recipients     = $this->getRecipientIds();
        $this->channels = [];
         $map            = [
            'Ajency\Comm\Models\EmailRecipient' => 'email',
            'Ajency\Comm\Models\SmsRecipient' => 'sms',
        ]; 
        foreach ($recipients as $recipient) {
            if(get_class($recipient)=="Ajency\Comm\Models\AjCommUserCommunication"){
                $recipient_attributes = $recipient->getattributes();
                $this->channels[] =  ($recipient_attributes['type']);
            }
            else{
                  $this->channels[] = $map[get_class($recipient)];

            }
            //$this->channels[] = $map[get_class($recipient)];
        }


        return $this->channels;
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


    public function getRecipients($channel)
    {
        $map = [
            'email' => 'Ajency\Comm\Models\EmailRecipient',
            'sms' => 'Ajency\Comm\Models\SmsRecipient',
        ];
        $channel_class = $map[$channel];
        $recipients    = [];

        foreach ($this->recipient_ids as $recipient) {
            //if (get_class($recipient) == $channel_class) {
            if (get_class($recipient) == $channel_class || get_class($recipient) == 'Ajency\Comm\Models\AjCommUserCommunication') {
                $recipients[] = $recipient;
            } 

        }

        return $recipients;
    }

    /**
     * @param mixed $recipient_ids
     */

    public function setRecipientIds($recipient_ids)
    {
        $this->recipient_ids = $recipient_ids;
    }

    public function getDelay(){
        if(is_integer($this->delay) and $this->delay > 0 ) return $this->delay;
        else return 0; 
    }

    public function setDelay($delay){
        $this->delay = $delay;
    }

    public function getPriority(){
        return $this->priority;
    }

    public function setPriority($priority){
        $this->priority = ($priority == 'default' or $priority == 'high' or $priority == 'low')? $priority : 'default';
    }
}
