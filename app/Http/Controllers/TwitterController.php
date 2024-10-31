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
            'file' => 'nullable|file|mimetypes:image/jpeg,image/png,video/mp4|max:51200', // Allow videos as well
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            // Store the file in the specified path
            $filePath = $request->file('file')->store('uploads', 'public');
        }

        $response = $this->twitterService->postTweet($request->input('content'), $filePath);

        return response()->json($response);
    }
}
