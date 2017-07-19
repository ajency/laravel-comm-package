<?php
namespace Ajency\Comm\Models;

use Ajency\Comm\Providers\Laravel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SubscriberEmail extends Model
{
    protected $table = 'aj_comm_emails'; //can make this a config?


    protected $attributes = [
        'ref_type' => 'User',
        'is_primary' => 0,
    ];

    /**
     * @return mixed
     */
    public function getRefId()
    {
        return $this->attributes['ref_id'];
    }

    /**
     * @param mixed $ref_id
     */
    public function setRefId($ref_id)
    {
        $this->attributes['ref_id'] = isset($ref_id) ? $ref_id: Auth::id();
    }


    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->attributes['email'];
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->attributes['email'] = $email;
    }

    /**
     * @return mixed
     */
    public function getIsPrimary()
    {
        return $this->attributes['is_primary'];
    }

    /**
     * @param mixed $is_primary
     */
    public function setIsPrimary($is_primary)
    {
        $this->attributes['is_primary'] = $is_primary;
    }


    public function sendEmails($notification)
    {
        switch ($notification['provider']) {
            case 'laravel':
                $laravel = new Laravel();
                $laravel->sendNotification($notification);
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
