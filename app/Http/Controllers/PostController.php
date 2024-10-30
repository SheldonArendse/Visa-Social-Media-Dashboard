<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwitterService;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    protected $twitterService;

    public function __construct(TwitterService $twitterService)
    {
        $this->twitterService = $twitterService;
    }

    public function postToSocialMedia(Request $request)
    {
        // Validate input
        $request->validate([
            'content' => 'required|string|max:280',
            'image' => 'nullable|image|max:2048',
        ]);

        // Check if Twitter is selected
        if ($request->has('twitter')) {
            $content = $request->input('content');

            // Handle image upload and path setup
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads', 'public');
                $imagePath = public_path('storage/' . $imagePath); // Get the absolute path

                // Check the file exists
                if (!file_exists($imagePath)) {
                    return redirect()->back()->with('error', 'Image upload failed. File not found.');
                }
            }

            // Post to Twitter with error handling
            try {
                $response = $this->twitterService->postTweet($content, $imagePath);

                if (isset($response['error'])) {
                    Log::error('Failed to post to Twitter: ' . $response['error']);
                    return redirect()->back()->with('error', 'Failed to post to Twitter.');
                }
                return redirect()->back()->with('success', 'Posted to Twitter successfully!');
            } catch (\Exception $e) {
                Log::error('Exception while posting to Twitter: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while posting to Twitter.');
            }
        }

        return redirect()->back()->with('error', 'No platform selected for posting.');
    }
}
