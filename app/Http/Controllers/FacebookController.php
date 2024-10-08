<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookService;

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

        // Validate the image and other inputs
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max size 2MB
            'content' => 'required|string',
            'links' => 'nullable|url', // Validates that the input is a valid URL
        ]);

        // Check if the user wants to post to Facebook
        if (in_array('facebook', $request->input('platforms'))) {
            // Store the image temporarily
            $image = $request->file('file');
            $imagePath = $image->store('uploads', 'public');

            // Append the link to the content if it's provided
            if ($links) {
                $content .= "\nMore info: " . $links; // Add the link to the content
            }

            // Use your Page Access Token and Page ID
            $pageAccessToken = 'EAAUrQQlB87YBO6V8pRt7kfXhyhx47cj3usOOAzbzMdelwp4rYExKlAcZAfi443E57ifnnWZCFG01Uf9qzLQVeHwGQRQPSEEDRL9TgPwO0fqLp8mUnTEGyKRryTZBhaQ8ZCMkwZBuAI4TWLVkUvC0J6ZCdmdFarl3LI4ur4fPY6IURv6aMKH2svQntWTJcW7xrI';
            $pageId = '412442358622297';

            // Call your Facebook service to post the image
            $response = $this->facebookService->postImageToFacebook($content, $imagePath, $pageAccessToken, $pageId);

            // Handle response and return success or error message
            if (isset($response['error'])) {
                return redirect()->back()->with('error', 'Failed to publish post: ' . $response['error']['message']);
            } else {
                return redirect()->back()->with('success', 'Post published successfully!');
            }
        }

        return redirect()->back()->with('error', 'Facebook platform not selected!');
    }
}
