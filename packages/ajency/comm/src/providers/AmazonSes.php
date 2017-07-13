<?php
namespace Ajency\Comm\Providers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Pushcrew {

    private $url = 'https://pushcrew.com/api/v1/send/individual';

    function sendNotification($message,$identity) {

    }
}