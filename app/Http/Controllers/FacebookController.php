<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookService;
use Illuminate\Support\Facades\Log;

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
        $links = $request->input('links'); // Get the URL links

        // Validate the inputs: image or video is now nullable
        $request->validate([
            'file' => 'nullable|mimes:jpeg,png,jpg,gif,mp4,avi,mov|max:102400', // Optional image or video, max 100 MB for videos
            'content' => 'required|string',
            'links' => 'nullable|url', // Optional valid URL
            'platforms' => 'required|array', // Ensure platforms is an array
        ]);

        // Check if the user selected Facebook as a platform
        if (in_array('facebook', $request->input('platforms'))) {
            // Append the link to the content if provided
            if ($links) {
                $content .= "\nMore info: " . $links;
            }

            $pageAccessToken = 'EAAUrQQlB87YBO6V8pRt7kfXhyhx47cj3usOOAzbzMdelwp4rYExKlAcZAfi443E57ifnnWZCFG01Uf9qzLQVeHwGQRQPSEEDRL9TgPwO0fqLp8mUnTEGyKRryTZBhaQ8ZCMkwZBuAI4TWLVkUvC0J6ZCdmdFarl3LI4ur4fPY6IURv6aMKH2svQntWTJcW7xrI';
            $pageId = '412442358622297';

            // Check if a file (image or video) is provided
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filePath = $file->store('uploads', 'public'); // Store the file (image or video)

                // Determine if the uploaded file is an image or video
                $mimeType = $file->getMimeType();

                try {
                    if (strstr($mimeType, 'image')) {
                        // Post with an image
                        $response = $this->facebookService->postImageToFacebook($content, $filePath, $pageAccessToken, $pageId);
                    } elseif (strstr($mimeType, 'video')) {
                        // Post with a video
                        $response = $this->facebookService->postVideoToFacebook($content, $filePath, $pageAccessToken, $pageId);
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
            } else {
                // Post only the message (and optionally the link)
                try {
                    $response = $this->facebookService->postMessageToFacebook($content, $pageAccessToken, $pageId);

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
}
