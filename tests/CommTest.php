<?php

class CommTest extends \Tests\TestCase
{
    public function testEmailSubscription()
    {
        $emailSubscriber = new EmailSubscriber();
        $emailSubscriber->setEmail('ajf7688@gmail.com');
        $communication_details = [$emailSubscriber];
        $result = AjComm::createSubscription($communication_details);
        $this->assertTrue($result['success']);
    }

    public function testMultipleSubscription()
    {
        $emailSubscriber = new EmailSubscriber();
        $emailSubscriber->setEmail('ajf7688@gmail.com');

        $emailSubscriber2 = new EmailSubscriber();
        $emailSubscriber2->setEmail('ajf7688+2@gmail.com');
        $emailSubscriber2->setRefId(23);

        $pushSub = new WebpushSubscriber();
        $pushSub->setSubscriberId('sdfsdfsfdsdfsf');
        $pushSub->setProvider('sdfsdfsfdsdfsf');

        $pushSub2 = new WebpushSubscriber();
        $pushSub2->setSubscriberId('sdfsdfsfdsdfsf');
        $pushSub2->setProvider('sdfsdfsfdsdfsf');
        $pushSub2->setRefId(23);

        $communication_details = [$emailSubscriber,$pushSub];
        $result = AjComm::createSubscription($communication_details);
        $this->assertTrue($result['success']);
    }


    public function testSendPush()
    {
        $notify = new \Ajency\Comm\Communication\Notification();
        $notify->setEvent('welcome');
        $notify->setProviderParams([
                'title' => 'Hi, Thank you for registering with Ajency push',
                'message' => 'Click here to know more about Push notification, edit your push settings using the gear icon above',
                'url' => 'http://127.0.0.1:8000/benefits',
                'image_url' => 'https://scontent.fdel1-1.fna.fbcdn.net/v/t1.0-1/c28.28.345.345/s50x50/485505_10151614542753486_1618802863_n.jpg?oh=d6831999d41e5e44c63ec62e0ac379f8&oe=59F74C83',
            ]);
        $notify->setRecipientIds([10]);
        $notify->setChannels(['web-push']);
        AjAuth::sendNotification($notify);
    }

    public function testErrorLogs()
    {
        # code...
    }

    public function testLogs()
    {
        # code...
    }
}
