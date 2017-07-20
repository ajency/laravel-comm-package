# Ajency Laravel Communication Package

- Version=1.0.2
- Updated=20 July 2017

## Installation

1. Create a folder /packages/ajency/comm Clone this repo in the folder in your Laravel project

2. In main project composer.json -> autoload -> psr-4 add
- "Ajency\\Comm\\": "packages/ajency/comm/src"

3. In config/app.php
- add 'Ajency\Comm\CommServiceProvider' under providers
- add 'AjComm' => 'Ajency\Comm\CommServiceProvider' under aliases

4. Copy over packages/ajency/comm/src/jobs/processEvents.php to app/Jobs/processEvents.php

5. Run commands
- composer dump-autoload
- php artisan vendor:publish

6. Run Migrations
- php artisan migrate
- *Caution : Laravel 5.4 has an issue with migrations regarding String length, please check this before running a migration on this version*

7. Configure the package
- in config/aj-comm-channels.php - Set your channel providers
- in config/aj-comm-events.php - Set your app events

## Supported providers
1. Laravel Email - Send email via SES, Mailgun or SMTP server
2. Pushcrew for push notifications

## Examples

1. Subscribe
    $email = new \Ajency\Comm\Models\EmailSubscriber();
    $email->setEmail('antonio+2dd2@ajency.in');
    $communication_details[] = $email;
    dd(AjComm::createSubscription($communication_details));

    $email = new \Ajency\Comm\Models\EmailSubscriber();
    $email->setEmail('antonio+2ddfdd2@ajency.in');
    $webpush = new \Ajency\Comm\Models\WebpushSubscriber();
    $webpush->setProvider('pushcrew');
    $webpush->setSubscriberId('09dfasdnasdna0d0a0dnad0adad9asd');
    $communication_details[] = $email;
    $communication_details[] = $webpush;
    dd(AjComm::createSubscription($communication_details));

1. Communication
    $notify = new \Ajency\Comm\Communication\Notification();
    $notify->setEvent('welcome');
    $notify->setRecepientIds(17);
    $notify->setProviderParams([
        'title' => 'Hi, Thank you for registering with Ajency push',
        'message' => 'Click here to know more about Push notification, edit your push settings using the gear icon above',
        'url' => 'http://127.0.0.1:8000/benefits',
        'image_url' => 'https://scontent.fdel1-1.fna.fbcdn.net/v/t1.0-1/c28.28.345.345/s50x50/485505_10151614542753486_1618802863_n.jpg?oh=d6831999d41e5e44c63ec62e0ac379f8&oe=59F74C83',
    ]);
    $notify->setChannels('web-push');
    dd(AjComm::sendNotification($notify));

## Changelog
* 1.0.2
** Exposed functions as package APIs
** Exposed objects to create subscriber and notification objects
* 1.0.1
** Error Logging
** Logging of provider requests
* 1.0.0
** Added Pushcrew and Laravel email as provider
** Ability to add email and push subscriptions