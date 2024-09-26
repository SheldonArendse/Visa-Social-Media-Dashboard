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
        $image = $request->file('file'); // Ensure this matches Dropzone's file name ('file')
        $platforms = $request->input('platforms');

        if (in_array('facebook', $platforms)) {
            // Use the new Page Access Token from the previous response
            $pageAccessToken = 'EAAUrQQlB87YBO6V8pRt7kfXhyhx47cj3usOOAzbzMdelwp4rYExKlAcZAfi443E57ifnnWZCFG01Uf9qzLQVeHwGQRQPSEEDRL9TgPwO0fqLp8mUnTEGyKRryTZBhaQ8ZCMkwZBuAI4TWLVkUvC0J6ZCdmdFarl3LI4ur4fPY6IURv6aMKH2svQntWTJcW7xrI';
            $pageId = '412442358622297'; // Ensure this is the correct Facebook Page ID

            // Post to the Facebook page using the service
            $response = $this->facebookService->postToPage($content, $image, $pageAccessToken, $pageId);

            // Check for errors in the response
            if (isset($response['error'])) {
                // Flash an error message to the session
                return redirect()->back()->with('error', 'Failed to publish post: ' . $response['error']['message']);
            } else {
                // Flash a success message to the session
                return redirect()->back()->with('success', 'Post published successfully!');
            }
        } else {
            // Flash an error message to the session if Facebook platform not selected
            return redirect()->back()->with('error', 'Facebook platform not selected!');
        }
    }
}
