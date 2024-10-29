<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class TwitterService
{
    protected $client;

    public function __construct()
    {
        // Set up OAuth 1.0a
        $stack = HandlerStack::create();
        $oauth = new Oauth1([
            'consumer_key'    => env('TWITTER_CONSUMER_KEY'),
            'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
            'token'           => env('TWITTER_ACCESS_TOKEN'),
            'token_secret'    => env('TWITTER_ACCESS_TOKEN_SECRET'),
            'signature_method' => Oauth1::SIGNATURE_METHOD_HMAC,
        ]);
        $stack->push($oauth);

        $this->client = new Client([
            'base_uri' => 'https://api.x.com/2/',
            'handler' => $stack,
            'auth' => 'oauth', // Enables OAuth for requests
        ]);
    }

    public function postTweet($message, $imagePath = null)
    {
        $url = 'tweets';
        $data = ['text' => $message];

        try {
            $response = $this->client->post($url, [
                'json' => $data,
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'response' => $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : null,
            ];
        }
    }
}
