<?php

namespace App\Jobs;

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
        $logError = false;

        //Switch case based on channel get the recepient id
        //We have 3 types of recepient ids currently 1. email, 2.sms, 3. push_ids
        //TODO, for multiple do we use another split queue in between??


        switch ($notification['channel']) {
            case 'web-push':
                $subscriber_id = DB::table('aj_comm_subscriber_webpush_ids')->where('provider',$notification['provider'])->where('user_id',$notification['recepients'][0] )->value('subscriber_id');

                //TODO - move to a file that can switch between multiple providers

                $pushcrew = new Pushcrew();
                $pushcrew->sendNotification($notification['provider_params'],$subscriber_id);


                break;
            case 'email':

                //TODO - move to a file that can switch between multiple providers
                $email_id = DB::table('aj_comm_subscriber_emails')->where('user_id',$notification['recepients'][0])->value('email');

                Mail::send('email.hello-user', $notification['provider_params'], function ($m) use ($email_id) {
                    $m->to($email_id)->subject('Welcome to the jungle!');
                });

/*                return response()->json(['message' => 'Request completed']);*/

                break;
            case 'mobile':
                $notification['mobile_no'] = DB::table('aj_comm_subscriber_mobile_nos')->where('user_id',$notification['recepients'][0])->value('mobile_no');
                break;
        }

        if($logError) {
            //Dispatch to error log - TODO
        }
    }

    /*
     * TODO
     * Seperate function for each channel provider
     * Jobs in package
     * Loggin oif failure and warning
     * Login of every message
     * Production queue
     */
}
