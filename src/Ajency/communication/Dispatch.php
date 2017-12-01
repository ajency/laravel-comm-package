<?php
namespace Ajency\Comm\Communication;

use Ajency\Comm\Models\EmailSubscriber;
use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\SmsSubscriber;
use Ajency\Comm\Models\WebpushSubscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
 * A class that handles the actiual sending of the notification after
 * Contains theprocess function to be executed once a notification job is ready to be processed
 */
class Dispatch
{

    /**
     * Contains parameters required to process an individual job
     *
     * @var string
     */
    private $notificationJob;

    /**
     * @return mixed
     */
    public function getNotificationJob()
    {
        return $this->notificationJob;
    }

    /**
     * @param $notificationJob
     * @internal param mixed $notification
     */
    public function setNotificationJob($notificationJob)
    {
        $this->notificationJob = $notificationJob;
    }

    /*
     * method that processed a single notification job
     *
     * Does not return any value and errors are logged using the error class
     *
     */
    public function processNotification()
    {
        $notification = $this->notificationJob;
        // dd($notification,get_class($notification['recipients'][0]));

        switch ($notification['channel']) {
            case 'web-push':
                $push          = new WebpushSubscriber();
                $subscriber_id = DB::table('aj_comm_webpush_ids')->where('provider', $notification['provider'])->where('ref_id', $notification['recipients'][0])->value('subscriber_id');
                if ($subscriber_id) {
                    $notification['subscriber_id'] = $subscriber_id;
                    $push->sendWebPushes($notification);
                } else {
                    $err = new Error();
                    $err->setMessage('recipient entitiy not found in aj_comm_webpush_ids table for user ID : ' . $notification['recipients'][0]);
                    $err->setLevel(2);
                    $err->setTag('not-found-sub-id');
                    $err->setUserId(Auth::id());
                    $err->save();
                }
                break;

            case 'email':
                $email = new EmailSubscriber();

                /*If subscriber objects are given on email object */
                $recipient_0   = $notification['recipients'][0];
                $toSubscribers = $recipient_0->getToSubscribers();

                if (count($toSubscribers) > 0) {

                    foreach ($toSubscribers as $subscriber) {
                        $sub_attributes    = $subscriber->getattributes();
                        $subscribers_email = DB::table('user_communications')
                            ->select('value')
                            ->where('object_id', $sub_attributes['object_id'])
                            ->where('type', $sub_attributes['type'])
                            ->where('object_type', $sub_attributes['object_type'])
                            ->where('is_communication', 1)
                            ->where('is_primary', 1)
                            ->first();

                        if ($subscribers_email != false & !is_null($subscribers_email)) {
                            $subscribers_emails_ids[] = $subscribers_email->value;
                        }

                    }
                    $notification['recipients'][0]->setTo($subscribers_emails_ids);
                }

                $email_id = $notification['recipients'][0];

                if ($email_id) {

                    $notification['email_id'] = $email_id;

                    $email->sendEmails($notification);
                } else {
                    $err = new Error();
                    $err->setMessage('recipient entitiy not found in aj_comm_emails table for user ID : ' . $notification['recipients'][0]);
                    $err->setLevel(2);
                    $err->setTag('not-found-email');
                    $err->setUserId(Auth::id());
                    $err->save();
                }
                break;

            case 'sms':
                $sms = new SmsSubscriber();
                $job = $notification['recipients'][0];

                /*if subscriber objects are provided on sms object */
                $toSubscribers = $job->getToSubscribers();

                $subscribers_no_ids = [];

                if (count($toSubscribers) > 0) {

                    foreach ($toSubscribers as $subscriber) {
                        $sub_attributes = $subscriber->getattributes();
                        $subscriber_no  = DB::table('user_communications')
                            ->select('value')
                            ->where('object_id', $sub_attributes['object_id'])
                            ->where('type', $sub_attributes['type'])
                            ->where('object_type', $sub_attributes['object_type'])
                            ->where('is_communication', 1)
                            ->where('is_primary', 1)
                            ->first();

                        if ($subscriber_no != false & !is_null($subscriber_no)) {
                            $subscribers_no_ids[] = $subscriber_no->value;
                        }

                    }

                    $job->setTo($subscribers_no_ids);

                }

                if ($job) {
                    $notification['sms_id'] = $job;
                    $sms->sendSms($notification);
                } else {
                    $err = new Error();
                    $err->setMessage('Unknown Sms  : ' . $notification['recipients'][0]);
                    $err->setLevel(2);
                    $err->setTag('not-found-sms');
                    $err->setUserId(Auth::id());
                    $err->save();
                }

                break;
        }
    }
}
