<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwitterService;

class TwitterController extends Controller
{
    protected $twitterService;

    public function __construct(TwitterService $twitterService)
    {
        $this->twitterService = $twitterService;
    }

    public function postToTwitter(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:280',
        ]);

        $result = $this->twitterService->postTweet($request->input('message'));

        return response()->json($result);
    }
}
