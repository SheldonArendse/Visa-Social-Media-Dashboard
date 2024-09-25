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
        $image = $request->file('file'); // 'file' is the Dropzone name for uploaded files
        $platforms = $request->input('platforms');

        if (in_array('facebook', $platforms)) {
            // Use the new Page Access Token from the previous response
            $pageAccessToken = 'EAAUrQQlB87YBO9g0HTRePOigWlDrIhJRhDRbkHEURTI6VQDvsWPhZAi1uItUBXdi3IltxmExFS6SYOZBWXiZBwRD1QLTBZCfQjVZAoSZAFgS6lozPGZAohuCeex3qBwnCXpZAzIZC6ZCUXMsYPUpyWO0KQ89fgWyBYQ2QpvQZAVu7nVAiBAaiUPVMgv4pLNwB6nyQ7jA32C2CsRgcb5eTMCHokAcOk8';
            $pageId = '412442358622297'; // Ensure this is the correct Facebook Page ID

            // Post to the Facebook page using the service
            $response = $this->facebookService->postToPage($content, $image, $pageAccessToken, $pageId);

            if (isset($response['error'])) {
                return response()->json(['message' => 'Failed to publish post: ' . $response['error']], 500);
            } else {
                return response()->json(['message' => 'Post published successfully!']);
            }
        } else {
            return response()->json(['message' => 'Facebook platform not selected!'], 400);
        }
    }
}
