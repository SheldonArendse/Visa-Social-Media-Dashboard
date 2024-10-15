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

        // Validate the image, content, links, and platforms
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max size 2MB
            'content' => 'required|string',
            'links' => 'nullable|url', // Validates that the input is a valid URL
            'platforms' => 'required|array', // Ensure platforms is an array
        ]);

        // Check if the user selected Facebook as a platform
        if (in_array('facebook', $request->input('platforms'))) {
            // Store the image temporarily
            $image = $request->file('file');
            $imagePath = $image->store('uploads', 'public');

            // Append the link to the content if it's provided
            if ($links) {
                $content .= "\nMore info: " . $links; // Add the link to the content
            }

            $pageAccessToken = 'EAAUrQQlB87YBO6V8pRt7kfXhyhx47cj3usOOAzbzMdelwp4rYExKlAcZAfi443E57ifnnWZCFG01Uf9qzLQVeHwGQRQPSEEDRL9TgPwO0fqLp8mUnTEGyKRryTZBhaQ8ZCMkwZBuAI4TWLVkUvC0J6ZCdmdFarl3LI4ur4fPY6IURv6aMKH2svQntWTJcW7xrI';
            $pageId = '412442358622297';

            // Call the Facebook service to post the image
            try {
                $response = $this->facebookService->postImageToFacebook($content, $imagePath, $pageAccessToken, $pageId);

                // Log and handle the response
                Log::info('Facebook API response: ', $response);

                // Check for error in the response
                if (isset($response['error'])) {
                    Log::error('Failed to publish post: ' . $response['error']['message']);
                    return redirect()->back()->with('error', 'Failed to publish post: ' . $response['error']['message']);
                } else {
                    Log::info('Post published successfully');
                    return redirect()->back()->with('success', 'Post published successfully!');
                }
            } catch (\Exception $e) {
                // Log the exception message and return an error message
                Log::error('Exception while posting to Facebook: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while posting to Facebook: ' . $e->getMessage());
            }
        }

        // If Facebook platform is not selected, store an error message
        Log::warning('Facebook platform not selected!');
        return redirect()->back()->with('error', 'Facebook platform not selected!');
    }
}
