<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Jobs\ScheduleFacebookPost;

class FacebookController extends Controller
{
    protected $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    public function postToFacebook(Request $request)
    {
        $content = $request->input('content');
        $links = $request->input('links');
        $scheduledTime = $request->input('scheduled_time');
        $action = $request->input('action'); // Get the action (post now or schedule)

        // Validate the inputs
        $request->validate([
            'file' => 'nullable|mimes:jpeg,png,jpg,gif,mp4,avi,mov|max:102400',
            'content' => 'required|string',
            'links' => 'nullable|url',
            'platforms' => 'required|array',
            'scheduled_time' => 'nullable|date|after:now', // Ensure the scheduled time is valid and in the future
        ]);

        // Check if the user selected Facebook as a platform
        if (in_array('facebook', $request->input('platforms'))) {
            // Append the link to the content if provided
            if ($links) {
                $content .= "\nMore info: " . $links;
            }

            // Prepare the post data
            $postData = [
                'content' => $content,
                'file' => $request->hasFile('file') ? $request->file('file') : null,
                'pageAccessToken' => 'EAAUrQQlB87YBO6V8pRt7kfXhyhx47cj3usOOAzbzMdelwp4rYExKlAcZAfi443E57ifnnWZCFG01Uf9qzLQVeHwGQRQPSEEDRL9TgPwO0fqLp8mUnTEGyKRryTZBhaQ8ZCMkwZBuAI4TWLVkUvC0J6ZCdmdFarl3LI4ur4fPY6IURv6aMKH2svQntWTJcW7xrI',
                'pageId' => '412442358622297',
            ];

            // Handle the action based on the button clicked
            if ($action === 'schedule_post') {
                // If a scheduled time is provided, dispatch a job to schedule the post
                if ($scheduledTime) {
                    $scheduledTime = Carbon::parse($scheduledTime);
                    ScheduleFacebookPost::dispatch($postData)->delay($scheduledTime);
                    return redirect()->back()->with('success', 'Post scheduled successfully!');
                }
                return redirect()->back()->with('error', 'Scheduled time is required to schedule a post.');
            }

            // If the action is to post immediately
            if ($request->hasFile('file')) {
                // Handle media posting (image or video)
                return $this->handleMediaPost($postData);
            } else {
                // Post only the message
                try {
                    $response = $this->facebookService->postMessageToFacebook($content, $postData['pageAccessToken'], $postData['pageId']);

                    if (isset($response['error'])) {
                        Log::error('Failed to publish post: ' . $response['error']['message']);
                        return redirect()->back()->with('error', 'Failed to publish post: ' . $response['error']['message']);
                    }
                    return redirect()->back()->with('success', 'Message published successfully!');
                } catch (\Exception $e) {
                    Log::error('Exception while posting to Facebook: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'An error occurred while posting to Facebook: ' . $e->getMessage());
                }
            }
        }

        return redirect()->back()->with('error', 'Facebook platform not selected.');
    }

    private function handleMediaPost($postData)
    {
        $file = $postData['file'];
        $filePath = $file->store('uploads', 'public'); // Store the file (image or video)
        $mimeType = $file->getMimeType();

        try {
            if (strstr($mimeType, 'image')) {
                $response = $this->facebookService->postImageToFacebook($postData['content'], $filePath, $postData['pageAccessToken'], $postData['pageId']);
            } elseif (strstr($mimeType, 'video')) {
                $response = $this->facebookService->postVideoToFacebook($postData['content'], $filePath, $postData['pageAccessToken'], $postData['pageId']);
            }

            // Handle response errors
            if (isset($response['error'])) {
                Log::error('Failed to publish post: ' . $response['error']['message']);
                return redirect()->back()->with('error', 'Failed to publish post: ' . $response['error']['message']);
            }
            return redirect()->back()->with('success', 'Post published successfully with media!');
        } catch (\Exception $e) {
            Log::error('Exception while posting to Facebook: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while posting to Facebook: ' . $e->getMessage());
        }
    }
}
