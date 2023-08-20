<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageReplyController;
use App\Http\Controllers\MetaDataController;
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

    Route::controller(MetaDataController::class)->group(
        function () {
            Route::get('/meta_data', 'index')
                ->name('meta_data.index');
            Route::get('/meta_data/{meta_data}/edit', 'edit')
                ->name('meta_data.edit');
            Route::get('/meta_data/create', 'create')
                ->name('meta_data.create');
            Route::post('/meta_data/store', 'store')
                ->name('meta_data.store');
            Route::put('/meta_data/{meta_data}/update', 'update')
                ->name('meta_data.update');
        }
    );

    Route::controller(MessageReplyController::class)->group(
        function () {
            Route::put('/message_reply/{message}/update', 'reply')
                ->name('message_reply.reply');
        }
    );

    Route::controller(MessageController::class)->group(
        function () {
            Route::get('/messages/create', 'create')->name('messages.create');
            Route::post('/messages/store', 'store')->name('messages.store');
            Route::put('/messages/{message}/update', 'update')
                ->name('messages.update');
            Route::get('/messages/{message}', 'show')->name('messages.show');
            Route::get('/messages/{message}/edit', 'edit')->name('messages.edit');
        }
    );
});
