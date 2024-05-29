<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SubscriptionHistoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Models\Article;
use App\Models\SubscriptionHistory;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Login, register and logout
Route::post('/authenticate', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register'])->name('user.register');
Route::get('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
// Route::patch('/delete-avatar/{user}', [UserController::class, 'removeAvatar'])->middleware('auth:sanctum')->name('avatar.delete');

// Authenticated routes
$guestRoutes = ['index', 'show'];

Route::middleware('auth:sanctum')->group(function () use ($guestRoutes) {
    Route::apiResource('/articles', ArticleController::class)->except($guestRoutes);
    Route::apiResource('/comments', CommentController::class)->except($guestRoutes);
    Route::apiResource('/users', UserController::class)->except($guestRoutes);
    Route::apiResource('/tags', TagController::class)->except($guestRoutes);
    Route::apiResource('/histories', SubscriptionHistoryController::class);
    Route::get('/history/my', [SubscriptionHistoryController::class, 'my'])->name('blog.my');
    Route::get('/history/my/active', [SubscriptionHistoryController::class, 'myActive'])->name('blog.active');
    Route::post('/history/subscribe', [SubscriptionHistoryController::class, 'subscribe'])->name('blog.subscribe');
    Route::post('/history/unsubscribe', [SubscriptionHistoryController::class, 'unsubscribe'])->name('blog.unsubscribe');


    Route::post('/my/upload-url', [UserController::class, 'uploadAvatar'])->name('my.upload_avatar');
    Route::get('/my', [UserController::class, 'my'])->middleware('auth:sanctum');
});

// Unauthenticated routes
Route::apiResource('/articles', ArticleController::class)->only($guestRoutes);
// endpoint to get only premium articles
Route::apiResource('/comments', CommentController::class)->only($guestRoutes);
Route::apiResource('/users', UserController::class)->only($guestRoutes);
Route::apiResource('/tags', TagController::class)->only($guestRoutes);
Route::get('/premium-articles', [ArticleController::class, 'premiumArticles'])->name('articles.premium');
Route::get('/premium-tags', [TagController::class, 'premiumTags'])->name('tags.premium');
Route::get('/tag', [TagController::class, 'byTag']);

Route::get('/articles/{article}/comments', [CommentController::class, 'show'])->name('articles.comments.show');
