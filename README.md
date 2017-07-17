#Ajency Laravel Communication Package -
Version = 1.0.0 - 15 July 2017

Installation
Create a folder /packages/ajency/comm
Clone this repo in the folder


In main project composer.json -> autoload -> psr-4
- "Ajency\\Comm\\": "packages/ajency/comm/src",
- "Ajency\\Comm\\Models\\": "packages/ajency/comm/src/models/",
- "Ajency\\Comm\\API\\": "packages/ajency/comm/src/api/",
- "Ajency\\Comm\\Providers\\": "src/providers/"

In config/app.php
- add 'Ajency\Comm\CommServiceProvider' under providers

Copy over packages/ajency/comm/src/jobs/processEvents.php to app/Jobs/processEvents.php

Run cmds
- composer dump-autoload
- php artisan vendor:publish
- php artisan migrate

Set config
- in config/aj-comm-channels.php - Set your channel providers
- in config/aj-comm-events.php - Set your app events

Email Providers supported
- Laravel
-- Set your provider settings from .env

Mobile Providers supported
- NONE

Webpush Providers supported
- Clevertap

Examples for subscriptions

    $emails[] = 'antonio+3434@ajency.in';
    $emails[] =  [ 'email' => 'antoniosdfsdf@ajency.in', 'is_primary' => true ];
    $emails[] =  [ 'email' => 'antoniosdfsdf@ajency.in' ];
    $emails[] =  [ 'email' => 'antoniosdfsdfsdf@sdfsdfsdfsfd.in', 'is_primary' => true , 'user_id' => 55 ];
    $emails[] = [
        [ 'email' => 'antonio+3@ajency.in', 'is_primary' => true, 'user_id' => 55 ],
        [ 'email' => 'antonio+3@ajency.in', 'is_primary' => true ],
        [ 'email' => 'antonio+4@ajency.in' ]
    ];

    $mobiles[] = '9900190516';
    $mobiles[] =  [ 'mobile_no' => '9900190516', 'is_primary' => true ];
    $mobiles[] =  [ 'mobile_no' => '9900590516' ];
    $mobiles[] =  [ 'mobile_no' => '9910190516', 'is_primary' => true , 'user_id' => 78 ];
    $mobiles[] = [
        [ 'mobile_no' => '9900190516', 'is_primary' => true, 'user_id' => 55 ],
        [ 'mobile_no' => '9900190517', 'is_primary' => true ],
        [ 'mobile_no' => '9900190518' ]
    ];


    $webpuses[] =  [ 'provider_id' => 'sfdsdfssdfsdfsddfsdf', 'provider_key' => 'clevertap' ];
    $webpuses[] =  [ 'provider_id' => 'sdfsdfsdfsdfsfdsdf', 'provider_key' => 'clevertap' , 'user_id' => 55 ];
    $webpuses[] = [
        [ 'provider_id' => 'sdfsdfsdfwerwervwewr', 'provider_key' => 'pushcrew', 'user_id' => 55 ],
        [ 'provider_id' => 'wersdsdfsdfsdfsdfsdfsf', 'provider_key' => 'sdfsdfsdf' ],
        [ 'provider_id' => 'werwerwefsdfsdfsdfsdf', 'provider_key' => 'sdfsdfsdf' ]
    ];

    $sub = new \Ajency\Comm\API\Subscription();
    $sub->setUserId(223);
    $sub->setEmails($emails[0]);
    $sub->setMobileNos($mobiles[2]);
    $sub->setWebpushIds($webpuses[2]);
    $sub->create_or_update_subscription();


Examples for sending notifications

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
    $notify = new \Ajency\Comm\API\Notification();
    $notify->send_notification($event,[9]);

    $event = [
        'event' => 'welcome',
        'provider_params' => [
            'name' => 'Antonio',
        ],
        'channels' => ['email']
    ];
    $notify = new \Ajency\Comm\API\Notification();
    $notify->send_notification($event,[10]);
