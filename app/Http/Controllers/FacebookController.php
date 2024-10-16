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

        // Validate the inputs: image is now nullable
        $request->validate([
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image
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

            // Check if an image is provided
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imagePath = $image->store('uploads', 'public');

                // Post with an image
                try {
                    $response = $this->facebookService->postImageToFacebook($content, $imagePath, $pageAccessToken, $pageId);

                    if (isset($response['error'])) {
                        Log::error('Failed to publish post: ' . $response['error']['message']);
                        return redirect()->back()->with('error', 'Failed to publish post: ' . $response['error']['message']);
                    }
                    return redirect()->back()->with('success', 'Post published successfully with an image!');
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

        return redirect()->back()->with('error', 'Facebook platform not selected!');
    }
}
