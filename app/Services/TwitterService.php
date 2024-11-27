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
            'base_uri' => 'https://api.twitter.com/2/',
            'handler' => $stack,
            'auth' => 'oauth',
        ]);

        $this->mediaClient = new Client([
            'base_uri' => 'https://upload.twitter.com/1.1/',
            'handler' => $stack,
            'auth' => 'oauth',
        ]);
    }

    public function postTweet($message, $filePath = null)
    {
        $mediaId = null;
        if ($filePath) {
            $mediaId = $this->uploadMedia($filePath);
        }

        $data = [
            'text' => $message,
            'media' => $mediaId ? ['media_ids' => [$mediaId]] : null,
        ];

        try {
            $response = $this->client->post('tweets', [
                'json' => array_filter($data),
            ]);

            // Check for rate limit headers
            $rateLimitReset = $response->getHeader('x-rate-limit-reset')[0];
            $resetTime = \Carbon\Carbon::createFromTimestamp($rateLimitReset);
            Log::info('Rate limit will reset at: ' . $resetTime->toDateTimeString());

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


    private function uploadMedia($filePath)
    {
        $filePath = storage_path("app/public/{$filePath}");
        $fileMimeType = mime_content_type($filePath);
        $url = 'https://upload.twitter.com/1.1/media/upload.json';

        try {
            if (str_starts_with($fileMimeType, 'video/')) {
                return $this->chunkedMediaUpload($filePath, 'tweet_video');
            } else {
                $response = $this->mediaClient->post($url, [
                    'multipart' => [
                        ['name' => 'media', 'contents' => fopen($filePath, 'r')],
                    ],
                ]);
                return json_decode($response->getBody(), true)['media_id_string'];
            }
        } catch (RequestException $e) {
            Log::error('Media upload failed: ' . $e->getMessage());
            throw new \Exception('Media upload failed: ' . $e->getMessage());
        }
    }

    private function chunkedMediaUpload($filePath, $mediaCategory)
    {
        $totalBytes = filesize($filePath);
        $url = 'https://upload.twitter.com/1.1/media/upload.json';

        try {
            // Step 1: INIT the upload - provides meta data
            $initResponse = $this->mediaClient->post($url, [
                'form_params' => [
                    'command' => 'INIT',
                    'media_type' => 'video/mp4',
                    'total_bytes' => $totalBytes,
                    'media_category' => $mediaCategory,
                ],
            ]);
            $mediaId = json_decode($initResponse->getBody(), true)['media_id_string'];

            // Step 2: APPEND the media
            // Loops through each segment and apprends it to the upload
            $segmentIndex = 0;
            $file = fopen($filePath, 'r');
            while (!feof($file)) {
                $chunk = fread($file, 5 * 1024 * 1024);
                $this->mediaClient->post($url, [
                    'multipart' => [
                        ['name' => 'command', 'contents' => 'APPEND'],
                        ['name' => 'media_id', 'contents' => $mediaId],
                        ['name' => 'segment_index', 'contents' => $segmentIndex],
                        ['name' => 'media', 'contents' => $chunk],
                    ],
                ]);
                $segmentIndex++;
            }
            fclose($file);

            // Step 3: FINALIZE the upload - Tells Twitter to start uploading the video
            $finalizeResponse = $this->mediaClient->post($url, [
                'form_params' => [
                    'command' => 'FINALIZE',
                    'media_id' => $mediaId,
                ],
            ]);

            $finalizeData = json_decode($finalizeResponse->getBody(), true);
            Log::info('Chunked media upload response: ' . json_encode($finalizeData));

            // Step 4: Check processing status
            // Monitors the upload status (pending, in_progress or succeeded)
            $checkAfterSecs = $finalizeData['processing_info']['check_after_secs'] ?? 1;
            do {
                sleep($checkAfterSecs);

                $statusResponse = $this->mediaClient->get($url, [
                    'query' => [
                        'command' => 'STATUS',
                        'media_id' => $mediaId,
                    ],
                ]);

                $statusData = json_decode($statusResponse->getBody(), true);
                $processingState = $statusData['processing_info']['state'] ?? 'succeeded';

                if ($processingState === 'succeeded') {
                    return $mediaId;
                } elseif ($processingState === 'failed') {
                    throw new \Exception('Media processing failed: ' . json_encode($statusData));
                }

                $checkAfterSecs = $statusData['processing_info']['check_after_secs'] ?? 1;
            } while ($processingState === 'pending' || $processingState === 'in_progress');
        } catch (RequestException $e) {
            Log::error('Media upload failed: ' . $e->getMessage());
            throw new \Exception('Media upload failed: ' . $e->getMessage());
        }
    }
}
