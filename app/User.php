<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    public static function boot() {

        parent::boot();

        static::created(function($user) {

            print_r($user);
            $event = [
                'event' => 'welcome',
                'provider_params' => [
                    'name' => 'Antonio',
                ],
                'channels' => ['email']
            ];
            $notify = new \Ajency\Comm\API\Notification();
            $notify->send_notification($event,[$user->id]);
        });
    }

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
