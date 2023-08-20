<?php

namespace Tests\Feature\Http\Controllers;

use App\Mail\MagicSignIn;
use App\Models\LoginToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SignedUrlAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_ignores()
    {
        Mail::fake();
        $this->post('/api/signed', [
            'email' => 'foo@bar.com',
        ])->assertStatus(200);

        $this->assertDatabaseCount('login_tokens', 0);
        Mail::assertNothingQueued();
    }

    public function test_creats_token_and_returns_200()
    {
        Mail::fake();
        $user = User::factory()->create([
            'email' => 'foo@bar.com',
        ]);

        $this->post('/api/signed', [
            'email' => 'foo@bar.com',
        ])->assertStatus(200);

        $this->assertDatabaseCount('login_tokens', 1);
    }

    public function test_sends_email()
    {
        Mail::fake();
        $user = User::factory()->create([
            'email' => 'foo@bar.com',
        ]);

        $this->post('/api/signed', [
            'email' => 'foo@bar.com',
        ])->assertStatus(200);

        $this->assertDatabaseCount('login_tokens', 1);
        Mail::assertQueued(function (MagicSignIn $mail) use ($user) {
            $loginToken = LoginToken::where('user_id', $user->id)->first();

            return $mail->loginToken->id === $loginToken->id;
        });
    }

    public function test_logs_user_in()
    {
        $user = User::factory()->nonAdmin()->create([
            'email' => 'foo@bar.com',
        ]);

        $loginToken = LoginToken::factory()->create(
            ['user_id' => $user->id, 'consumed_at' => null]
        );

        $url = $loginToken->signed_url;

        $this->get($url);

        $this->assertAuthenticatedAs($user);
    }

    public function test_token_used()
    {
        $user = User::factory()->create([
            'email' => 'foo@bar.com',
        ]);

        $loginToken = LoginToken::factory()->create(
            ['user_id' => $user->id, 'consumed_at' => now()]
        );

        $url = $loginToken->signed_url;

        $this->get($url)->assertStatus(401);
    }
}
