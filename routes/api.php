<?php

use App\Http\Controllers\SignedUrlAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post(
    '/signed', [SignedUrlAuth::class, 'create']
)->name('signed_url.create');


Route::get('/upwork/callback', function (Request $request) {
    $authorizationCode = $request->get('code');

    logger("Code coming in " . $authorizationCode);
    // With this authorization code, you can now request an access token
    // by sending a POST request to the authorization server with your clientId, clientSecret
    // grantType('authorization_code'), redirectUri, and authorizationCode you just received

    // Make sure to handle errors appropriately and securely store access tokens.

    return "OK";
});
