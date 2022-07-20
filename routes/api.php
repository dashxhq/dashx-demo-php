<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\PostController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['as' => 'api.'], function() {
    Route::post('/contact', ContactController::class)->name('contact');

    Route::group(['middleware' => 'guest:api'], function() {
        Route::post('/register', RegisteredUserController::class)->name('register');
        Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
        Route::post('/forgot-password', ForgotPasswordController::class)->name('forgot-password');
        Route::post('/reset-password', ResetPasswordController::class)->name('reset-password');
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
        Route::post('/refresh-token', [AuthenticationController::class, 'refresh'])->name('refresh-token');

        Route::get('/profile', [UserController::class, 'show'])->name('profile');
        Route::patch('/update-profile', [UserController::class, 'update'])->name('update-profile');

        Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
        Route::put('/posts/{post}/toggle-bookmark', [PostController::class, 'toggleBookmark'])->name('posts.toggleBookmark');
        Route::get('/posts/bookmarked', [PostController::class, 'getBookmarkedPosts'])->name('posts.bookmarked');
    });
});
