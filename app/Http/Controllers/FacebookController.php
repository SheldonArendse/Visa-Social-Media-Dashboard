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

    public function redirectToFacebook()
    {
        $clientId = env('FACEBOOK_APP_ID');
        $redirectUri = urlencode(route('facebook.callback'));
        $scope = 'pages_manage_posts,publish_to_pages';

        return redirect("https://www.facebook.com/v12.0/dialog/oauth?client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}&response_type=code");
    }

    public function handleCallback(Request $request)
    {
        $code = $request->input('code');
        $clientId = env('FACEBOOK_APP_ID');
        $clientSecret = env('FACEBOOK_APP_SECRET');
        $redirectUri = urlencode(route('facebook.callback'));

        $client = new \GuzzleHttp\Client();
        $response = $client->post("https://graph.facebook.com/v12.0/oauth/access_token", [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'code' => $code,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents());
        session(['fb_access_token' => $data->access_token]);

        return redirect('/dashboard')->with('status', 'Logged in to Facebook!');
    }
}
