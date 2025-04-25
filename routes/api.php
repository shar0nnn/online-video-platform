<?php

use App\Http\Controllers\API\Public\Auth\GoogleAuthController;
use App\Http\Controllers\API\Public\Auth\LoginController;
use App\Http\Controllers\API\Public\HomeController;
use App\Http\Controllers\API\User\VideoController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('videos', [VideoController::class, 'index'])->name('videos.index');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return 'dashboard';
        })->name('dashboard');
    });
});




