<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\User\AccountController;
use App\Http\Controllers\Frontend\User\ProfileController;
use App\Http\Controllers\Frontend\User\DashboardController;
use App\Http\Middleware\RedirectForSeo;
/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', [HomeController::class, 'index'])->name('index');

Route::middleware(RedirectForSeo::class)->group(function() {
    Route::get('search/{slug}', [HomeController::class, 'search'])->name('search');
    Route::get('sq{slug}/{page?}', [HomeController::class, 'category'])->name('category')->where('page', '[0-9]+\.html');
});

Route::get('av/{slug}.html', [HomeController::class, 'show'])->name('show');
Route::get('img/{slug}', [\App\Http\Controllers\Frontend\ImageController::class, 'meipai'])->name('image');

Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('contact/send', [ContactController::class, 'send'])->name('contact.send');
Route::post('q',[HomeController::class, 'userSearch'])->name('userSearch');

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 * These routes can not be hit if the password is expired
 */
Route::group(['middleware' => ['auth', 'password_expires']], function () {
    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        /*
         * User Dashboard Specific
         */
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        /*
         * User Account Specific
         */
        Route::get('account', [AccountController::class, 'index'])->name('account');

        /*
         * User Profile Specific
         */
        Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    });
});
