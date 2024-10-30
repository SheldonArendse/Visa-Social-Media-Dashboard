<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Support\Facades\Log;

class TwitterService
{
    protected $client;
    protected $mediaClient;

    public function __construct(Client $client)
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
            'base_uri' => 'https://api.twitter.com/2/', // v2 base URI
            'handler' => $stack,
            'auth' => 'oauth',
        ]);

        $this->mediaClient = new Client([
            'base_uri' => 'https://upload.twitter.com/1.1/', // v1.1 base URI for media
            'handler' => $stack,
            'auth' => 'oauth',
        ]);
    }

    public function postTweet($message, $imagePath = null)
    {
        // If an image path is provided, upload the media first
        $mediaId = null;
        if ($imagePath) {
            $mediaId = $this->uploadMedia($imagePath);
        }

        // Prepare data for the tweet
        $data = [
            'text' => $message,
            'media' => $mediaId ? ['media_ids' => [$mediaId]] : null,
        ];

        // Remove null values
        $data = array_filter($data);

        try {
            $response = $this->client->post('tweets', [
                'json' => $data,
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('Twitter post request failed: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'response' => $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : null,
            ];
        }
    }

    private function uploadMedia($imagePath)
    {
        $url = 'media/upload.json';
        try {
            $response = $this->mediaClient->post($url, [
                'multipart' => [
                    [
                        'name' => 'media',
                        'contents' => fopen(storage_path("app/public/{$imagePath}"), 'r'),
                    ]
                ],
            ]);

            $mediaResponse = json_decode($response->getBody(), true);
            Log::info('Media upload response: ' . json_encode($mediaResponse));
            return $mediaResponse['media_id_string'];
        } catch (RequestException $e) {
            Log::error('Media upload failed: ' . $e->getMessage());
            throw new \Exception('Media upload failed: ' . $e->getMessage());
        }
    }
}
