<?php
namespace Ajency\Comm\API;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Subscriber_Webpush_Id;
use App\Jobs\processEvents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ajency\Comm\Models\Subscriber_Email;

class Notification {

    public static function send_notification($event, $user_ids){
        $provider = new Provider();
        $jobs = $provider->getProvidersForEvent($event,$user_ids);
        foreach($jobs as $job) {
            dispatch(new processEvents($job));
        }
    }

    public static function channel_split($notification) {

        /*
         * TODO - Roadmap
         * For multiple users with a single message this will need rework
         * We have kept extendability open by making $notification['recepients'] an array
         * Currectly we have hardcoded $notification['recepients'][0] to work with on one item
         */
        switch ($notification['channel']) {
            case 'web-push':
                $push = new Subscriber_Webpush_Id();
                $subscriber_id = DB::table('aj_comm_subscriber_webpush_ids')->where('provider',$notification['provider'])->where('user_id',$notification['recepients'][0] )->value('subscriber_id');
                if($subscriber_id) {
                    $notification['subscriber_id'] = $subscriber_id;
                    $push->send($notification);
                } else {
                    $err = new Error();
                    $err->setMessage('Recepient entitiy not found in aj_comm_subscriber_webpush_ids table for user ID : '. $notification['recepients'][0]);
                    $err->setLevel(2);
                    $err->setTag('not-found-sub-id');
                    $err->setUserId(Auth::id());
                    $err->save();
                }
                break;
            case 'email':

                $email = new Subscriber_Email();
                $email_id = DB::table('aj_comm_subscriber_emails')->where('user_id',$notification['recepients'][0])->value('email');
                if($email_id) {
                    $notification['email_id'] = $email_id;
                    $email->send($notification);
                } else {
                    $err = new Error();
                    $err->setMessage('Recepient entitiy not found in aj_comm_subscriber_emails table for user ID : '. $notification['recepients'][0]);
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