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
            $pageAccessToken = 'EAAUrQQlB87YBO6YoedwZCWJ4lOIK2Kviwgz2u5cBupPrc3FoOvsCtnr846qZBQUZCrbMxtJN7ZBr5jXiXmbxs1qCZCPMZAAtmZBZBb5Y11IE1xvYdB6wlQMhZAtEqU77Rpv56SaF6WKlFWZBaNZCleZBoucjvkl2SMKcZAZCfdtF3vtrs8HhzK2zyvdh1rfvklYaAmdBSzZCr0j3GahZCMTH3fd0Q5GeqqDftAZDZD';
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
