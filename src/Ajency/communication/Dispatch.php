<?php
namespace Ajency\Comm\Communication;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\WebpushSubscriber;
use Ajency\Comm\Models\SmsSubscriber;
use App\Jobs\processEvents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ajency\Comm\Models\EmailSubscriber;


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
                $push = new WebpushSubscriber();
                $subscriber_id = DB::table('aj_comm_webpush_ids')->where('provider', $notification['provider'])->where('ref_id', $notification['recipients'][0])->value('subscriber_id');
                if ($subscriber_id) {
                    $notification['subscriber_id'] = $subscriber_id;
                    $push->sendWebPushes($notification);
                } else {
                    $err = new Error();
                    $err->setMessage('recipient entitiy not found in aj_comm_webpush_ids table for user ID : '. $notification['recipients'][0]);
                    $err->setLevel(2);
                    $err->setTag('not-found-sub-id');
                    $err->setUserId(Auth::id());
                    $err->save();
                }
                break;

            case 'email':
                $email = new EmailSubscriber();
                $email_id =  $notification['recipients'][0];
                if ($email_id) {
                    $notification['email_id'] = $email_id;
                    $email->sendEmails($notification);
                } else {
                    $err = new Error();
                    $err->setMessage('recipient entitiy not found in aj_comm_emails table for user ID : '. $notification['recipients'][0]);
                    $err->setLevel(2);
                    $err->setTag('not-found-email');
                    $err->setUserId(Auth::id());
                    $err->save();
                }
                break;

            case 'sms':
                $sms = new SmsSubscriber();
                $job = $notification['recipients'][0];
                if($job){
                    $notification['sms_id'] = $job;
                    $sms->sendSms($notification);
                } else {
                    $err = new Error();
                    $err->setMessage('Unknown Sms  : '. $notification['recipients'][0]);
                    $err->setLevel(2);
                    $err->setTag('not-found-sms');
                    $err->setUserId(Auth::id());
                    $err->save();
                }

                break;
        }
    }
}
