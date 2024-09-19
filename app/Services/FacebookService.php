<?php

namespace App\Services;

use GuzzleHttp\Client;

class FacebookService
{
    protected $client;
    protected $accessToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->accessToken = env('FACEBOOK_PAGE_ACCESS_TOKEN');
    }

    public function postToPage($message, $imageUrl = null)
    {
        $url = 'https://graph.facebook.com/v12.0/me/feed';

        $params = [
            'form_params' => [
                'access_token' => $this->accessToken,
                'message' => $message,
            ]
        ];

        if ($imageUrl) {
            $params['form_params']['picture'] = $imageUrl;
        }

        $response = $this->client->post($url, $params);

        return json_decode($response->getBody()->getContents());
    }
}
