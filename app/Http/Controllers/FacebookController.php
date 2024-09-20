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

    /**
     * Handle the request to post to Facebook.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function postToFacebook(Request $request)
    {
        $accessToken = session('fb_access_token');

        if (!$accessToken) {
            return redirect()->route('facebook.login');
        }

        try {
            // Call the Facebook service to post to the page
            $message = $request->input('message');
            $image = $request->file('image'); // Optional image upload
            $response = $this->facebookService->postToPage($message, $image, $accessToken);

            if (isset($response['id'])) {
                return redirect('/dashboard')->with('status', 'Posted successfully to Facebook!');
            } else {
                return redirect('/dashboard')->with('error', 'Failed to post to Facebook.');
            }
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Redirect the user to Facebook for authentication.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToFacebook()
    {
        $clientId = env('FACEBOOK_APP_ID');
        $redirectUri = urlencode(route('facebook.callback'));
        $scope = 'pages_manage_posts,pages_manage_photos';

        return redirect("https://www.facebook.com/v12.0/dialog/oauth?client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}&response_type=code");
    }

    /**
     * Handle Facebook OAuth callback and get access token.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleCallback(Request $request)
    {
        $code = $request->input('code');
        $clientId = env('FACEBOOK_APP_ID');
        $clientSecret = env('FACEBOOK_APP_SECRET');
        $redirectUri = route('facebook.callback');

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post("https://graph.facebook.com/v12.0/oauth/access_token", [
                'form_params' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'redirect_uri' => $redirectUri,
                    'code' => $code,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            session(['fb_access_token' => $data['access_token']]);

            return redirect('/dashboard')->with('status', 'Logged in to Facebook!');
        } catch (\Exception $e) {
            return redirect('/dashboard')->with('error', 'Failed to get access token.');
        }
    }
}
