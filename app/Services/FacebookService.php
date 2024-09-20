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

    /**
     * Post a message or an image to the Facebook page.
     *
     * @param string $message The post message
     * @param mixed $image The image file (optional)
     * @param string $accessToken The Facebook access token
     * @return array The response from Facebook
     */
    public function postToPage($message, $image = null, $accessToken)
    {
        // Check if we're posting an image or a text post
        if ($image) {
            // Upload the image to a public storage location and get the URL
            $imagePath = $image->store('public/uploads');
            $imageUrl = Storage::url($imagePath);

            // Post the image to the Facebook page
            return $this->postImageToFacebook($message, $imageUrl, $accessToken);
        } else {
            // Post only text to the Facebook page
            return $this->postMessageToFacebook($message, $accessToken);
        }
    }

    /**
     * Post a text message to the Facebook page.
     *
     * @param string $message The post message
     * @param string $accessToken The Facebook access token
     * @return array The response from Facebook
     */
    private function postMessageToFacebook($message, $accessToken)
    {
        $url = 'https://graph.facebook.com/v12.0/me/feed';

        $params = [
            'access_token' => $accessToken,
            'message' => $message,
        ];

        $response = $this->client->post($url, [
            'form_params' => $params
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Post an image to the Facebook page.
     *
     * @param string $message The post message (caption)
     * @param string $imageUrl The image URL
     * @param string $accessToken The Facebook access token
     * @return array The response from Facebook
     */
    private function postImageToFacebook($message, $imageUrl, $accessToken)
    {
        $photoUrl = 'https://graph.facebook.com/v12.0/me/photos';

        $params = [
            'access_token' => $accessToken,
            'url' => url($imageUrl),
            'caption' => $message,
        ];

        $response = $this->client->post($photoUrl, [
            'form_params' => $params
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
