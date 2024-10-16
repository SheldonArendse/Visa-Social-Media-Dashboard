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
            Log::info('Facebook API response (image): ' . $responseBody);
            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            Log::error('Error posting image to Facebook: ' . $e->getMessage());
            return ['error' => ['message' => 'Error posting image to Facebook: ' . $e->getMessage()]];
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
            Log::info('Facebook API response (message): ' . $responseBody);

            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            Log::error('Error posting message to Facebook: ' . $e->getMessage());
            return ['error' => ['message' => 'Failed to post message to Facebook']];
        }
    }

    // New method to post a video to Facebook
    public function postVideoToFacebook($message, $videoPath, $accessToken, $pageId)
    {
        $videoUrl = "https://graph.facebook.com/v20.0/{$pageId}/videos";

        try {
            $response = $this->client->post($videoUrl, [
                'multipart' => [
                    [
                        'name'     => 'access_token',
                        'contents' => $accessToken
                    ],
                    [
                        'name'     => 'description',
                        'contents' => $message
                    ],
                    [
                        'name'     => 'file',
                        'contents' => fopen(storage_path("app/public/{$videoPath}"), 'r'),
                        'filename' => basename($videoPath)
                    ]
                ]
            ]);

            $responseBody = $response->getBody()->getContents();
            Log::info('Facebook API response (video): ' . $responseBody);
            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            Log::error('Error posting video to Facebook: ' . $e->getMessage());
            return ['error' => ['message' => 'Error posting video to Facebook: ' . $e->getMessage()]];
        }
    }
}
