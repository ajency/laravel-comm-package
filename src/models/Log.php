<?php
namespace Ajency\Comm\Models;

use Illuminate\Database\Eloquent\Model;


class Log extends Model
{
    protected $table = 'aj_comm_logs'; //can make this a config?

    /**
     * @return mixed
     */
    public function getNotificationData()
    {
        return $this->attributes['notification_data'];
    }

    /**
     * @param mixed $notification_data
     */
    public function setNotificationData($notification_data)
    {
        $this->attributes['notification_data'] = $notification_data;
    }


    /**
     * @return mixed
     */
    public function getApi()
    {
        return $this->attributes['api'];
    }

    /**
     * @param mixed $api
     */
    public function setApi($api)
    {
        $this->attributes['api'] = $api;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->attributes['user_id'];
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->attributes['user_id'] = isset($user_id) ? $user_id : 1;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->attributes['request'];
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->attributes['request'] = $request;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->attributes['response'];
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->attributes['response'] = $response;
    }
}
