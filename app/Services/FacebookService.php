<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FacebookService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function postImageToFacebook($message, $imagePath, $accessToken, $pageId)
    {
        $photoUrl = "https://graph.facebook.com/v20.0/{$pageId}/photos";

        // Use the image path in the multipart request
        try {
            $response = $this->client->post($photoUrl, [
                'multipart' => [
                    [
                        'name'     => 'access_token',
                        'contents' => $accessToken
                    ],
                    [
                        'name'     => 'caption',
                        'contents' => $message
                    ],
                    [
                        'name'     => 'file',
                        'contents' => fopen(storage_path("app/public/{$imagePath}"), 'r'),
                        'filename' => basename($imagePath)
                    ]
                ]
            ]);

            $responseBody = $response->getBody()->getContents();
            Log::info('Facebook API response: ' . $responseBody);
            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            Log::error('Error posting image to Facebook: ' . $e->getMessage());
            return ['error' => 'Error posting image to Facebook: ' . $e->getMessage()];
        }
    }



    public function postMessageToFacebook($message, $accessToken, $pageId)
    {
        $postUrl = "https://graph.facebook.com/v20.0/{$pageId}/feed";

        try {
            $response = $this->client->post($postUrl, [
                'form_params' => [
                    'message' => $message,
                    'access_token' => $accessToken,
                ]
            ]);

            $responseBody = $response->getBody()->getContents();
            Log::info('Facebook API response: ' . $responseBody);

            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            Log::error('Error posting message to Facebook: ' . $e->getMessage());
            return ['error' => ['message' => 'Failed to post message to Facebook']];
        }
    }
}
