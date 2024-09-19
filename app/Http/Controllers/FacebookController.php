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
        $accessToken = session('fb_access_token');

        if (!$accessToken) {
            return redirect()->route('facebook.login');
        }

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post('https://graph.facebook.com/v12.0/412442358622297/feed', [
                'form_params' => [
                    'message' => $request->input('message'),
                    'access_token' => $accessToken,
                ],
            ]);

            return redirect('/dashboard')->with('status', 'Posted successfully to Facebook!');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
