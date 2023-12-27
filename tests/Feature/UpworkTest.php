<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpworkTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_connects(): void
    {
        $config = new \Upwork\API\Config(
            array(
                'clientId'          => env("UPWORK_KEY"), // SETUP YOUR CONSUMER KEY
                'clientSecret'      => env("UPWORK_SECRET"), // SETUP KEY SECRET
                //'grantType'         => 'client_credentials', // used in Client Credentials Grant
                'redirectUri'       => 'https://e538b8057c8e.ngrok.app/api/upwork/callback', // used in Code Authorization Grant
                //'accessToken'       => null, // WARNING: keep this up-to-date!
                //'refreshToken'      => null, // WARNING: keep this up-to-date! // NOT needed for Client Credentials Grant
                //'expiresIn'         => null, // WARNING: keep this up-to-date!
                //'debug'             => true, // enables debug mode
                //'authType'          => 'MyOAuthPHPLib' // your own authentication type, see AuthTypes directory
            )
        );

        $client = new \Upwork\API\Client($config);
    //$client::setOrgUidHeader('1234567890'); // Organization UID (optional)

    // $accessTokenInfo has the following structure
    // array('access_token' => ..., 'refresh_token' => ..., 'expires_in' => ...);
    // keeps the access token in a secure place
    // gets info of authenticated user
        $accessTokenInfo = $client->auth();

        dd($accessTokenInfo);
    }
}
