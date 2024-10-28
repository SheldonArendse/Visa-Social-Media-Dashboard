<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookService; // Include this if you are still using Facebook functionality
use App\Services\TwitterService;

class PostController extends Controller
{
    protected $facebookService;
    protected $twitterService;

    public function __construct(FacebookService $facebookService, TwitterService $twitterService)
    {
        $this->facebookService = $facebookService;
        $this->twitterService = $twitterService;
    }

    public function postToSocialMedia(Request $request)
    {
        // Post to Facebook if selected
        if ($request->has('facebook')) {
            // Your existing Facebook posting logic
        }

        // Post to Twitter if selected
        if ($request->has('twitter')) {
            return $this->twitterService->postTweet($request);
        }

        return redirect()->back()->with('error', 'No platform selected for posting.');
    }
}
