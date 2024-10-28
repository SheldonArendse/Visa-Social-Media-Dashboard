<?php

namespace App\Services;

use GuzzleHttp\Client;

class TwitterService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function postTweet($message, $imagePath = null)
    {
        $url = 'https://api.twitter.com/2/tweets';
        $data = ['text' => $message];

        if ($imagePath) {
            // Handle media upload if required
        }

        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . env('TWITTER_BEARER_TOKEN'),
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);

        return json_decode($response->getBody(), true);
    }
}
