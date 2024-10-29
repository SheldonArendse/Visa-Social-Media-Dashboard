<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookService; // Include this if you are still using Facebook functionality
use App\Services\TwitterService;

class PostController extends Controller
{
    protected $facebookService;
    protected $twitterService;

    public function postToSocialMedia(Request $request)
    {
        // Post to Facebook if selected
        if ($request->has('facebook')) {
            // Add Facebook posting logic here
        }

        // Post to Twitter if selected
        if ($request->has('twitter')) {
            $content = $request->input('content');
            // $imagePath = $request->hasFile('image') ? $request->file('image')->store('uploads', 'public') : null;
            $response = $this->twitterService->postTweet($content);

            return response()->json($response);
        }

        return redirect()->back()->with('error', 'No platform selected for posting.');
    }
}
