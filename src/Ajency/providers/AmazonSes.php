<?php
namespace Ajency\Comm\Providers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/*
 * Amazon SES provider class
 */
class AmazonSes
{
    private $url = '';

    public function sendNotification($message, $identity)
    {
    }
}
