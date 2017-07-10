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


Route::get('test2', function () {

    $not = new \Ajency\Comm\Providers\Pushcrew();
    $msg['title'] = 'dfasdasdasdasdasd';
    $msg['message'] = 'asdasd asd asd asd asd asd asd asd asd asd a';
    $msg['url'] = '/welcome';
    $msg['image_url'] = 'https://learninglaravel.net/img/logo.png';
    $not->sendNotification($msg,'ff9b05c170f427dbfe38fbfee7f8abe0');
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
