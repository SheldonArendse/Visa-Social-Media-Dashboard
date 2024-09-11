<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

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

require __DIR__ . '/auth.php';
