<?php
namespace Ajency\Comm\Providers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Mandrill
{
    private $url = '';

    public function sendNotification($message, $identity)
    {
    }
}
