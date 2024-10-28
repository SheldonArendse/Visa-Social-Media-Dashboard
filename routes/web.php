<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\TwitterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/analytics', function () {
    return view('analytics');
})->name('analytics');

// Middleware to check if user is authenticated
Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Articles route
    Route::get('/articles', function () {
        return view('articles');
    })->name('articles');

    // Route for posting to Facebook
    Route::post('/facebook/post', [FacebookController::class, 'postToFacebook']);

    // Route for posting to Twitter
    Route::post('/twitter/post', [TwitterController::class, 'postToTwitter'])->name('twitter.post');
    // Route::post('/twitter/post', [TwitterController::class, 'postToTwitter']);

    // Multi-platform post route
    Route::post('/social-media/post', [PostController::class, 'postToSocialMedia'])->name('socialMedia.post');

    // Facebook OAuth routes
    Route::get('/login/facebook', function () {
        $fbOAuthUrl = 'https://www.facebook.com/v20.0/dialog/oauth';
        $params = [
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'redirect_uri' => env('FACEBOOK_REDIRECT_URL'),
            'scope' => 'pages_manage_posts',
            'response_type' => 'code',
        ];
        $loginUrl = $fbOAuthUrl . '?' . http_build_query($params);
        return redirect()->away($loginUrl);
    })->name('facebook.login');

    Route::get('/facebook/callback', function (Request $request) {
        Log::info('Facebook callback received', $request->all());
        $code = $request->get('code');
        if (!$code) {
            return 'Error: Unable to get authorization code';
        }

        // Exchange the code for an access token
        $client = new Client();
        $response = $client->post('https://graph.facebook.com/v20.0/oauth/access_token', [
            'query' => [
                'client_id' => env('FACEBOOK_CLIENT_ID'),
                'redirect_uri' => env('FACEBOOK_REDIRECT_URL'),
                'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
                'code' => $code,
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        $accessToken = $data['access_token'];

        // Store access token in session
        session(['fb_access_token' => $accessToken]);

        return redirect('/dashboard')->with('status', 'Facebook connected successfully!');
    });

    // Route to post to Facebook after OAuth
    Route::post('/post-to-facebook', function (Request $request) {
        $accessToken = session('fb_access_token');
        if (!$accessToken) {
            return redirect()->route('facebook.login');
        }

        $data = [
            'message' => $request->input('message'),
            'link' => $request->input('link'), // Optional link
        ];

        $client = new Client();
        try {
            $response = $client->post('https://graph.facebook.com/v20.0/{page-id}/feed', [
                'form_params' => $data + [
                    'access_token' => $accessToken,
                ],
            ]);
            return redirect('/dashboard')->with('status', 'Posted successfully to Facebook!');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    })->name('post.facebook');
});

require __DIR__ . '/auth.php';
