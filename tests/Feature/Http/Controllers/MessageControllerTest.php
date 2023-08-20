<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    public function test_create()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('messages.create'))
            ->assertStatus(200);
    }

    public function test_store()
    {
        $user = User::factory()->create();

        $this->assertDatabaseCount('messages', 0);
        $this->actingAs($user)->post(route('messages.store'), [
            'message' => 'Foo bar',
        ]);
        $this->assertDatabaseCount('messages', 1);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $message = Message::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->get(route('messages.show', [
            'message' => $message->id,
        ]))
            ->assertStatus(200);
    }
}
