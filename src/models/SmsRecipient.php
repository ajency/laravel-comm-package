<?php
namespace Ajency\Comm\Models;

class SmsRecipient
{
    const TYPE = 'sms';

    private $to             = [];
    private $to_subscribers = [];
    private $message;
    private $override_dnd = false;
    private $data         = [];
    private $attributes   = ['data' => []];

    public function setTo($to)
    {
        $this->to = (is_array($to)) ? $to : [$to];
    }

    public function setToSubscribers($to)
    {
        $this->to_subscribers = (is_array($to)) ? $to : [$to];
    }

    public function setOverride($override)
    {
        $this->override_dnd = $override;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getToSubscribers()
    {
        return $this->to_subscribers;
    }

    public function addTo($to)
    {
        $this->to[] = $to;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getOverride()
    {
        return $this->override_dnd;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setAttributes($data)
    {
        $this->attribures = $data;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

}
