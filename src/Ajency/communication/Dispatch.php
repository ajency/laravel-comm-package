<?php
namespace Ajency\Comm\Communication;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\WebpushSubscriber;
use App\Jobs\processEvents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ajency\Comm\Models\EmailSubscriber;
use Ajency\Comm\Models\MobileSubscriber;


/*
 * A base class that lets us define Communication methods
 * Communication methods are any methods utilized to send notification via queue process
 */
class Dispatch
{
    private $notificationJob;

    /**
     * @return mixed
     */
    public function getNotificationJob()
    {
        return $this->notificationJob;
    }

    /**
     * @param mixed $notification
     */
    public function setNotificationJob($notificationJob)
    {
        $this->notificationJob = $notificationJob;
    }


    /*
     * A single API call instance
     * Calls providers
     */
    public function processNotification()
    {
        /*
         * TODO - Roadmap
         * For multiple users with a single message this will need rework
         * We have kept extendability open by making $notification['recepients'] an array
         * Currectly we have hardcoded $notification['recepients'][0] to work with on one item
         */
        $notification = $this->notificationJob;

        switch ($notification['channel']) {
            case 'web-push':
                $push = new WebpushSubscriber();
                $subscriber_id = DB::table('aj_comm_webpush_ids')->where('provider', $notification['provider'])->where('ref_id', $notification['recepients'][0])->value('subscriber_id');
                if ($subscriber_id) {
                    $notification['subscriber_id'] = $subscriber_id;
                    $push->sendWebPushes($notification);
                } else {
                    $err = new Error();
                    $err->setMessage('Recepient entitiy not found in aj_comm_webpush_ids table for user ID : '. $notification['recepients'][0]);
                    $err->setLevel(2);
                    $err->setTag('not-found-sub-id');
                    $err->setUserId(Auth::id());
                    $err->save();
                }
                break;

            case 'email':
                $email = new EmailSubscriber();
                $email_id = DB::table('aj_comm_emails')->where('ref_id', $notification['recepients'][0])->value('email');
                if ($email_id) {
                    $notification['email_id'] = $email_id;
                    $email->sendEmails($notification);
                } else {
                    $err = new Error();
                    $err->setMessage('Recepient entitiy not found in aj_comm_emails table for user ID : '. $notification['recepients'][0]);
                    $err->setLevel(2);
                    $err->setTag('not-found-email');
                    $err->setUserId(Auth::id());
                    $err->save();
                }
                break;

            case 'mobile':

                //TODO

                break;
        }
    }
}