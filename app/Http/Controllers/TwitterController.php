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
            'file' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('file')) {
            // Store the file in the specified path
            $imagePath = $request->file('file')->store('uploads', 'public'); // Ensure 'uploads' matches your desired directory
            // Make sure you are referencing the correct path later
            // $imagePath = public_path('storage/' . $imagePath);
        }

        $response = $this->twitterService->postTweet($request->input('content'), $imagePath);

        return response()->json($response);
    }
}
