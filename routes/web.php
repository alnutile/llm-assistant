<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SignedUrlAuth;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login/signed/{token}', [SignedUrlAuth::class,
    'signInWithToken'])
    ->name('signed_url.login');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', HomeController::class)->name('dashboard');

    Route::controller(MessageController::class)->group(
        function() {
            Route::get("/messages/create", "create")->name('messages.create');
            Route::post("/messages/store", "store")->name('messages.store');
            Route::get("/messages/{message}", "show")->name('messages.show');
        }
    );
});
