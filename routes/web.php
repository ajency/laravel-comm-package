<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('test1', function () {
    $event = [
        'event' => 'welcome',
        'provider_params' => [
            /*'title' => 'Hi, Thank you for registering with Ajency push',*/
            'message' => 'Click here to know more about Push notification, edit your push settings using the gear icon above',
            'url' => 'http://127.0.0.1:8000/benefits',
            'image_url' => 'https://scontent.fdel1-1.fna.fbcdn.net/v/t1.0-1/c28.28.345.345/s50x50/485505_10151614542753486_1618802863_n.jpg?oh=d6831999d41e5e44c63ec62e0ac379f8&oe=59F74C83',
        ],
        'channels' => ['web-push']
    ];
    $notify = new \Ajency\Comm\API\Notification();
    $notify->send_notification($event,[26]);

});


Route::get('test6', function () {

    $email = 'antonio+22@ajency.in';
    $sub = new \Ajency\Comm\API\Subscription();
    $sub->setEmails($email);
    dd($sub->create_or_update_subscription());

});

Route::get('test3', function () {

    $event = [
        'event' => 'welcome',
        'provider_params' => [
            'name' => 'Antonio',
        ],
        'channels' => ['email']
    ];
    $notify = new \Ajency\Comm\API\Notification();
    $notify->send_notification($event,[10]);
});

Route::get('test2', function () {

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
    $notify->send_notification($event,[9]);
});

Route::get('test', function () {

    $emails[] = 'antonio+3434@ajency.in';
    $emails[] =  [ 'email' => 'antoniosdfsdf@ajency.in', 'is_primary' => true ];
    $emails[] =  [ 'email' => 'antoniosdfsdf@ajency.in' ];
    $emails[] =  [ 'email' => 'antoniosdfsdfsdf@sdfsdfsdfsfd.in', 'is_primary' => true , 'user_id' => 55 ];
    $emails[] = [
        [ 'email' => 'antonio+3@ajency.in', 'is_primary' => true, 'user_id' => 55 ],
        [ 'email' => 'antonio+3@ajency.in', 'is_primary' => true ],
        [ 'email' => 'antonio+4@ajency.in' ]
    ];

    $mobiles[] = '9900190516';
    $mobiles[] =  [ 'mobile_no' => '9900190516', 'is_primary' => true ];
    $mobiles[] =  [ 'mobile_no' => '9900590516' ];
    $mobiles[] =  [ 'mobile_no' => '9910190516', 'is_primary' => true , 'user_id' => 78 ];
    $mobiles[] = [
        [ 'mobile_no' => '9900190516', 'is_primary' => true, 'user_id' => 55 ],
        [ 'mobile_no' => '9900190517', 'is_primary' => true ],
        [ 'mobile_no' => '9900190518' ]
    ];


    $webpuses[] =  [ 'provider_id' => 'sfdsdfssdfsdfsddfsdf', 'provider_key' => 'clevertap' ];
    $webpuses[] =  [ 'provider_id' => 'sdfsdfsdfsdfsfdsdf', 'provider_key' => 'clevertap' , 'user_id' => 55 ];
    $webpuses[] = [
        [ 'provider_id' => 'sdfsdfsdfwerwervwewr', 'provider_key' => 'pushcrew', 'user_id' => 55 ],
        [ 'provider_id' => 'wersdsdfsdfsdfsdfsdfsf', 'provider_key' => 'sdfsdfsdf' ],
        [ 'provider_id' => 'werwerwefsdfsdfsdfsdf', 'provider_key' => 'sdfsdfsdf' ]
    ];

    $sub = new \Ajency\Comm\API\Subscription();
    $sub->setUserId(223);
    $sub->setEmails($emails[0]);
    $sub->setMobileNos($mobiles[2]);
    $sub->setWebpushIds($webpuses[2]);
    $sub->create_or_update_subscription();

});

// OAuth Routes
Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/benefits', 'HomeController@benefits');

Route::post('/subscription/web-push', 'SubscriptionController@createWebPushSubscription');
