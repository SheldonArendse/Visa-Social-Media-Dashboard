<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class FacebookService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function postToPage($message, $image = null, $accessToken)
    {
        $url = 'https://graph.facebook.com/v12.0/me/feed'; // Adjust to specific page if needed

        // Prepare form parameters
        $params = [
            'access_token' => $accessToken,
            'message' => $message,
        ];

        // Handle image upload
        if ($image) {
            // Upload the image to a public storage location
            $imagePath = $image->store('public/uploads');
            $imageUrl = Storage::url($imagePath);

            // Use Facebook's photo upload endpoint
            $photoUrl = 'https://graph.facebook.com/v12.0/me/photos';
            $response = $this->client->post($photoUrl, [
                'form_params' => [
                    'access_token' => $accessToken,
                    'url' => url($imageUrl), // Facebook expects a URL
                    'caption' => $message,
                ],
            ]);

            return json_decode($response->getBody()->getContents());
        }

        // Post without image
        $response = $this->client->post($url, [
            'form_params' => $params
        ]);

        return json_decode($response->getBody()->getContents());
    }
}
