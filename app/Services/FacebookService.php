<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FacebookService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Post a message or an image to the Facebook page.
     *
     * @param string $message The post message
     * @param mixed $image The image file (optional)
     * @param string $accessToken The Facebook access token
     * @param string $pageId The Facebook page ID
     * @return array The response from Facebook
     */
    public function postToPage($message, $image = null, $accessToken, $pageId)
    {
        try {
            // Check if we're posting an image or a text post
            if ($image) {
                // Store the image temporarily and get the path
                $imagePath = $image->store('public/uploads');
                $imagePath = str_replace('public/', '', $imagePath); // Remove 'public/' for storage_path

                // Post the image to the Facebook page
                return $this->postImageToFacebook($message, $imagePath, $accessToken, $pageId);
            } else {
                // Post only text to the Facebook page
                return $this->postMessageToFacebook($message, $accessToken, $pageId);
            }
        } catch (\Exception $e) {
            // Log the error and return a custom error response
            Log::error("Failed to post to Facebook: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Post a text message to the Facebook page.
     *
     * @param string $message The post message
     * @param string $accessToken The Facebook access token
     * @param string $pageId The Facebook page ID
     * @return array The response from Facebook
     */
    private function postMessageToFacebook($message, $accessToken, $pageId)
    {
        $url = "https://graph.facebook.com/v20.0/{$pageId}/feed";

        $params = [
            'access_token' => $accessToken,
            'message' => $message,
        ];

        $response = $this->client->post($url, [
            'form_params' => $params
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function postImageToFacebook($message, $imagePath, $accessToken, $pageId)
    {
        $photoUrl = "https://graph.facebook.com/v20.0/{$pageId}/photos";

        // Use the file path directly
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
                    'name'     => 'file', // The field name for the file
                    'contents' => fopen(storage_path("app/{$imagePath}"), 'r'), // Open the image file
                    'filename' => basename($imagePath) // Get the original filename
                ]
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
