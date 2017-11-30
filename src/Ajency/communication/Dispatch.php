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

                /*If subscriber ids are given on email object */
                $recipient_0   = $notification['recipients'][0];
                $toSubscribers = $recipient_0->getToSubscribers();

                if (count($toSubscribers) > 0) {
                    $subscribers_emails = DB::table('user_communications')->select('value')->whereIn('object_id', $toSubscribers)->where('type', 'email')->get();

                    foreach ($subscribers_emails as $subscriber_email) {
                        $subscribers_emails_ids[] = $subscriber_email->value;
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

                /*if subscriber ids are provided on sms object */
                $toSubscribers = $job->getToSubscribers();

               
                $subscribers_no_ids = [];

                if (count($toSubscribers) > 0) {
                    $subscribers_nos = DB::table('user_communications')->select('value')->whereIn('object_id', $toSubscribers)->where('type', 'sms')->get();


                    foreach ($subscribers_nos as $subscriber_no) {
                        $subscribers_no_ids[] = $subscriber_no->value;
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
