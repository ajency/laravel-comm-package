<?php

namespace App\Http\Controllers;

use Ajency\Comm\API\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWebPushSubscription(Request $request)
    {

        $id = Auth::user()->id;
        $sub_id = $request->get('subscriber_id');
        $pro_id = $request->get('provider');
        $web_push_ids[] =  [ 'provider' => $pro_id, 'subscriber_id' => $sub_id ];
        $sub = new Subscription();
        $sub->setWebpushIds($web_push_ids);
        $sub->create_or_update_subscription();


        //We are also sending a notification with some delay
        $event = [
            'event' => 'welcome',
            'provider_params' => [
                'title' => 'Hi, Thank you for registering with Ajency push',
                'message' => 'Click here to know more about Push notification, edit your push settings using the gear icon above',
                'url' => 'http://127.0.0.1:8000/benefits',
                'image_url' => 'https://scontent.fdel1-1.fna.fbcdn.net/v/t1.0-1/c28.28.345.345/s50x50/485505_10151614542753486_1618802863_n.jpg?oh=d6831999d41e5e44c63ec62e0ac379f8&oe=59F74C83',
            ],
            'channels' => ['web-push']
        ];
        $notify = new \Ajency\Comm\API\Notification();
        $notify->send_notification($event,[$id]);

        $response = array(
            'status' => 'success',
            'msg' => 'Setting created successfully',
        );
        return \Response::json($response);

    }
}
