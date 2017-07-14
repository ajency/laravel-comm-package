<?php
namespace Ajency\Comm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Log extends Model
{
    protected $table = 'aj_comm_log_table'; //can make this a config?
    private $user_id;
    private $request;
    private $response;
    private $notification_data;
    private $api;

    /**
     * @return mixed
     */
    public function getNotificationData()
    {
        return $this->notification_data;
    }

    /**
     * @param mixed $notification_data
     */
    public function setNotificationData($notification_data)
    {
        $this->notification_data = $notification_data;
    }





    /**
     * @return mixed
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param mixed $api
     */
    public function setApi($api)
    {
        $this->api = $api;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = isset($user_id) ? $user_id : 1;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }


}