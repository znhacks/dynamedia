<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Landing / Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/gallery', [PostController::class, 'index'])
    ->name('gallery');


/*
|--------------------------------------------------------------------------
| Guest Only (Belum Login)
|--------------------------------------------------------------------------
| Tidak bisa diakses jika sudah login
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'show'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'show'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Reset Password
    Route::get('/forgot-password', [LoginController::class, 'forgotPasswordForm'])
        ->name('password.request');

    Route::post('/forgot-password', [LoginController::class, 'sendPasswordReset'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [LoginController::class, 'resetPasswordForm'])
        ->name('password.reset');

    Route::post('/reset-password', [LoginController::class, 'resetPassword'])
        ->name('password.update');
});


/*
|--------------------------------------------------------------------------
| Auth Only (Sudah Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    /*
    | Profile
    */
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/profile/{id}', [ProfileController::class, 'show'])
        ->name('profile.show');


    /*
    | Posts
    */
    Route::get('/posts', [PostController::class, 'index'])
        ->name('posts.index');

    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('posts.create');

    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');

    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
        ->name('posts.edit');

    Route::put('/posts/{post}', [PostController::class, 'update'])
        ->name('posts.update');

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('posts.destroy');


    /*
    | Comments
    */
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
});


/*
|--------------------------------------------------------------------------
| Public Post View (Bisa Dilihat Tanpa Login)
|--------------------------------------------------------------------------
*/

Route::get('/posts/{post}', [PostController::class, 'show'])
    ->name('posts.show');
