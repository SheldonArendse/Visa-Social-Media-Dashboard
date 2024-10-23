<?php

namespace App\Jobs;

use App\Services\FacebookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScheduleFaceBookPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $postData;

    /**
     * Create a new job instance.
     *
     * @param array $postData
     */
    public function __construct(array $postData)
    {
        $this->postData = $postData;
    }

    /**
     * Execute the job.
     *
     * @param FacebookService $facebookService
     * @return void
     */
    public function handle(FacebookService $facebookService)
    {
        // Log the execution time to verify scheduling works
        Log::info('Scheduled Post Executed at: ' . now());

        // Extract post data
        $content = $this->postData['content'];
        $filePath = isset($this->postData['file']) ? $this->postData['file']->store('uploads', 'public') : null;
        $pageAccessToken = $this->postData['pageAccessToken'];
        $pageId = $this->postData['pageId'];

        try {
            // Log the post data being processed
            Log::info('Processing scheduled post data:', [
                'content' => $content,
                'filePath' => $filePath,
                'pageAccessToken' => $pageAccessToken,
                'pageId' => $pageId,
            ]);

            // Check if we have media to post
            if ($filePath) {
                $mimeType = $this->postData['file']->getMimeType();

                // Posting image or video based on mime type
                if (strstr($mimeType, 'image')) {
                    $response = $facebookService->postImageToFacebook($content, $filePath, $pageAccessToken, $pageId);
                } elseif (strstr($mimeType, 'video')) {
                    $response = $facebookService->postVideoToFacebook($content, $filePath, $pageAccessToken, $pageId);
                }
            } else {
                // If no media, post only the message
                $response = $facebookService->postMessageToFacebook($content, $pageAccessToken, $pageId);
            }

            // Log success or failure
            if (isset($response['error'])) {
                Log::error('Failed to publish scheduled post: ' . $response['error']['message']);
            } else {
                Log::info('Scheduled post published successfully.');
            }
        } catch (\Exception $e) {
            // Catch any exceptions and log the error
            Log::error('Exception while posting scheduled to Facebook: ' . $e->getMessage());
        }
    }
}
