<?php

namespace App\Jobs;

use Ajency\Comm\Models\Error;
use Ajency\Comm\Models\Subscriber_Email;
use Ajency\Comm\Models\Subscriber_Webpush_Id;
use Ajency\Comm\Providers\Pushcrew;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class processEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $processEventsArray;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($processEventsArray)
    {
        $this->processEventsArray = $processEventsArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notification = $this->processEventsArray;


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
                    $err->save();
                }


                break;
            case 'mobile':

                //TODO

                break;
        }
    }

    /*
     * TODO
     * Jobs in package
     * Loggin oif failure and warning
     * Login of every message
     * Production queue
     */
}
