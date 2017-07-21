#Ajency Laravel Communication Package - Version = 1.0.0 - 15 July 2017

Installation Create a folder /packages/ajency/comm Clone this repo in the folder

In main project composer.json -> autoload -> psr-4
"Ajency\\Comm\\": "packages/ajency/comm/src"


In config/app.php
add 'Ajency\Comm\CommServiceProvider' under providers
add 'AjComm' => 'Ajency\Comm\CommServiceProvider' under aliases

Copy over packages/ajency/comm/src/jobs/processEvents.php to app/Jobs/processEvents.php

Run cmds
composer dump-autoload
php artisan vendor:publish
php artisan migrate

Set config
in config/aj-comm-channels.php - Set your channel providers
in config/aj-comm-events.php - Set your app events

Email Providers supported

Laravel -- Set your provider settings from .env
Mobile Providers supported

NONE
Webpush Providers supported

Clevertap

Examples : Subscriptions

    $email = new \Ajency\Comm\Models\SubscriberEmail();
    $email->setEmail('antonio+2dd2@ajency.in');
    $communication_details[] = $email;
    dd(AjComm::createSubscription($communication_details));

    //With Multiple
     $email = new \Ajency\Comm\Models\SubscriberEmail();
     $email->setEmail('antonio+2ddfdd2@ajency.in');
     $webpush = new \Ajency\Comm\Models\SubscriberWebpushId();
     $webpush->setProvider('pushcrew');
     $webpush->setSubscriberId('09dfasdnasdna0d0a0dnad0adad9asd');
     $communication_details[] = $email;
     $communication_details[] = $webpush;
     dd(AjComm::createSubscription($communication_details));

Examples : Notifications

    $event = [
        'event' => 'welcome',
        'provider_params' => [
            'title' => 'Hi, Thank you for registering with Ajency push',
            'message' => 'Click here to know more about Push notification, edit your push settings using the gear icon above',
            'url' => 'http://127.0.0.1:8000/benefits',
            'image_url' => 'https://scontent.fdel1-1.fna.fbcdn.net/v/t1.0-1/c28.28.345.345/s50x50/485505_10151614542753486_1618802863_n.jpg?oh=d6831999d41e5e44c63ec62e0ac379f8&oe=59F74C83',
        ],
        'channels' => ['web-push']
    ];
    dd(AjComm::sendNotification($event,[26]));


