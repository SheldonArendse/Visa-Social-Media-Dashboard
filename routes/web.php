<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\FacebookController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Route for the analytics page
Route::get('/analytics', function () {
    return view('analytics');
})->name('analytics');

// Route for the articles page
Route::middleware('auth')->group(function () {
    Route::get('/articles', function () {
        return view('articles');
    })->name('articles');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Verify that user is logged in before posting
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');
});

// Route to post to Facebook using Guzzle
Route::post('/facebook/post', [FacebookController::class, 'postToFacebook'])->middleware('auth');

Route::middleware('auth')->group(function () {

    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Facebook OAuth Login route
    Route::get('/login/facebook', function () {
        $fbOAuthUrl = 'https://www.facebook.com/v12.0/dialog/oauth';
        $params = [
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'redirect_uri' => env('FACEBOOK_REDIRECT_URL'),
            'scope' => 'pages_manage_posts,publish_to_pages',
            'response_type' => 'code',
        ];

        $loginUrl = $fbOAuthUrl . '?' . http_build_query($params);

        return redirect()->away($loginUrl);
    })->name('facebook.login');

    // Facebook OAuth Callback route
    Route::get('/facebook/callback', function (Request $request) {
        $code = $request->get('code');

        if (!$code) {
            return 'Error: Unable to get authorization code';
        }

        // Exchange the code for an access token
        $client = new Client();
        $response = $client->post('https://graph.facebook.com/v12.0/oauth/access_token', [
            'query' => [
                'client_id' => env('FACEBOOK_CLIENT_ID'),
                'redirect_uri' => env('FACEBOOK_REDIRECT_URL'),
                'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
                'code' => $code,
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        $accessToken = $data['access_token'];

        // Store access token in session for future use
        session(['fb_access_token' => $accessToken]);

        return redirect('/dashboard')->with('status', 'Facebook connected successfully!');
    });

    // Route to post to Facebook using Guzzle
    Route::post('/post-to-facebook', function (Request $request) {
        $accessToken = session('fb_access_token');

        if (!$accessToken) {
            return redirect()->route('facebook.login');
        }

        // Prepare the data for the Facebook post
        $data = [
            'message' => $request->input('message'),
            'link' => $request->input('link'), // Optional link
        ];

        // Use Guzzle to post to the Facebook Graph API
        $client = new Client();
        try {
            // Post to Facebook page feed (replace {page-id} with the Facebook Page ID)
            $response = $client->post('https://graph.facebook.com/v12.0/412442358622297/feed', [
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
