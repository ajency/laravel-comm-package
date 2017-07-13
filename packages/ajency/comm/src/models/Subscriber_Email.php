<?php
namespace Ajency\Comm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Subscriber_Email extends Model
{
    protected $table = 'aj_comm_subscriber_emails'; //can make this a config?

    private $user_id;
    private $email;
    private $is_primary;

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
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getIsPrimary()
    {
        return $this->is_primary;
    }

    /**
     * @param mixed $is_primary
     */
    public function setIsPrimary($is_primary)
    {
        $this->is_primary = $is_primary;
    }


    function send($notification,$email_id) {



        switch ($notification['provider']) {
            case 'laravel':

                Mail::send('email.hello-user', $notification['provider_params'], function ($m) use ($email_id) {
                    $m->to($email_id)->subject('Welcome to the jungle!');
                });

                break;

            case 'mandrill':
                //TODO
                break;

            case 'sendgrid':
                //TODO
                break;
        }



    }



}