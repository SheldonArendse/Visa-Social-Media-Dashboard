<?php

namespace App\Http\Controllers;

use App\Services\FacebookService;
use Illuminate\Http\Request;

class FacebookController extends Controller
{
    protected $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    public function postToFacebook(Request $request)
    {
        // Check if platforms include Facebook
        if (in_array('facebook', $request->input('platforms', []))) {

            $accessToken = session('fb_access_token');

            // Redirect if no access token is found
            if (!$accessToken) {
                return redirect()->route('facebook.login');
            }

            // Get form inputs
            $message = $request->input('content');
            $image = $request->file('media'); // Assumes file is uploaded under 'media'

            // Call service to post on Facebook
            try {
                $result = $this->facebookService->postToPage($message, $image, $accessToken);

                return redirect('/dashboard')->with('status', 'Posted successfully to Facebook!');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'Error posting to Facebook: ' . $e->getMessage()]);
            }
        }

        return redirect('/dashboard')->with('status', 'No platforms selected.');
    }
}
