<?php

namespace App\Http\Controllers;

use App\Services\TwitterService;
use Illuminate\Http\Request;


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
            'content' => 'required|string|max:280',
            // 'image' => 'nullable|image|max:2048', 
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        $response = $this->twitterService->postTweet($request->content, $imagePath);

        return response()->json($response);
    }
}
