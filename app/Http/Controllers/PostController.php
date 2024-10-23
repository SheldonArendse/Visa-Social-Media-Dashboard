<?php

namespace App\Http\Controllers;

use App\Services\FacebookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    protected $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    public function schedulePost(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:500',
            'platforms' => 'required|array',
            'platforms.*' => 'in:facebook', // You can add more platforms as needed
            'scheduled_time' => 'required|date',
            'image' => 'sometimes|image|max:2048', // Max 2MB for images
            'video' => 'sometimes|mimetypes:video/mp4|max:100000' // Max 100MB for videos
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $message = $request->input('content');
        $platforms = $request->input('platforms');
        $scheduledTime = strtotime($request->input('scheduled_time')); // Convert to Unix timestamp
        $accessToken = 'EAAX...hG2H6'; // Your access token
        $pageId = '1454933211739062'; // Your page ID

        foreach ($platforms as $platform) {
            if ($platform === 'facebook') {
                // Check for image upload
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('uploads/images', 'public');
                    $this->facebookService->schedulePostToFacebook($message, $accessToken, $pageId, $scheduledTime, $imagePath);
                }

                // Check for video upload
                if ($request->hasFile('video')) {
                    $videoPath = $request->file('video')->store('uploads/videos', 'public');
                    $this->facebookService->postVideoToFacebook($message, $videoPath, $accessToken, $pageId);
                }

                // If no media is uploaded, just post the message
                if (!$request->hasFile('image') && !$request->hasFile('video')) {
                    $this->facebookService->postMessageToFacebook($message, $accessToken, $pageId);
                }
            }
        }

        return redirect()->back()->with('success', 'Post has been scheduled successfully!');
    }
}
