<?php
namespace Ajency\Comm\Providers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Pushcrew {

    private $url = 'https://pushcrew.com/api/v1/send/individual';

    function sendNotification($message,$identity) {
        $post = [
            "title" => $message['title'],
            "message" => $message['title'],
            "url" => $message['title'],
            "image_url" => $message['title'],
            "subscriber_id" => $identity
        ];
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: key=f694d03321eca313a4030b091b942ec4'
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        var_dump($response);
    }
}