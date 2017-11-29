# Ajency Laravel Communication Package

- Version=1.0.2
- Updated=20 July 2017

## Installation

1. Create a folder /packages/ajency/comm Clone this repo in the folder in your Laravel project

2. In main project composer.json -> autoload -> psr-4 add "Ajency\\Comm\\": "packages/ajency/comm/src"

3. In config/app.php add 'Ajency\Comm\CommServiceProvider' under providers

4. In config/app.php add 'AjComm' => 'Ajency\Comm\CommServiceProvider' under aliases

5. Copy over packages/ajency/comm/src/jobs/processEvents.php to app/Jobs/processEvents.php

5. Run composer dump-autoload

6. Run php artisan vendor:publish

7. Run php artisan migrate

8. Set your channel providers in config/aj-comm-channels.php

9. Set your channel providers in config/aj-comm-events.php - Set your app events

*Caution : Laravel 5.4 has an issue with migrations regarding String length, please check this before running a migration on 5.4 version*

## Supported providers
1. Laravel Email - Send email via SES, Mailgun or SMTP server
2. Pushcrew for push notifications

## Examples
Email Example
```php
   $email = new \Ajency\Comm\Models\EmailRecipient();
        $email->setFrom('valenie@ajency.in', 'Project Manager');
        $email->setTo(['harshita@ajency.in','shashank@ajency.in']);
        $email->setCc('sharath@ajency.in');
        $email->setBcc('nutan@ajency.in');
        $email->setParams([
            'name' => 'Shashank',
        'subject' => 'Welcome to the jungle!',
        ]);
        $email1 = new \Ajency\Comm\Models\EmailRecipient();
        $email1->setFrom('sharath@ajency.in', 'Developer');
        $email1->setTo('valenie@ajency.in');
        $email1->setCc(['harshita@ajency.in','shashank@ajency.in']);
        $email1->setBcc('nutan@ajency.in');
        $email1->setParams([
            'name' => 'valenie',
        'subject' => 'Welcome to the jungle!',
        ]);
        $notify = new \Ajency\Comm\Communication\Notification();
        $notify->setEvent('welcome');
        $notify->setRecipientIds([$email,$email1]);
        AjComm::sendNotification($notify);
```

SMS Example
```php
        $sms = new \Ajency\Comm\Models\SmsRecipient();
        $sms->setTo(['919158514761','917789456585']);
        $sms->setMessage('Hi, Welcome to FnB Circle');
        $sms->setOverride(true);// to send to DND numbers
        $notify = new \Ajency\Comm\Communication\Notification();
        $notify->setEvent('new-user');
        $notify->setRecipientIds([$sms]);
        AjComm::sendNotification($notify);
```

You can set delay and priorities
```php
    $notify->setDelay(20);//integer value in minutes
    $notify->setPriority('high'); // accepted values ['low','default','high']

```

## Changelog
- 1.0.2
-- Exposed functions as package APIs
-- Exposed objects to create subscriber and notification objects
- 1.0.1
-- Error Logging
-- Logging of provider requests
- 1.0.0
-- Added Pushcrew and Laravel email as provider
-- Ability to add email and push subscriptions