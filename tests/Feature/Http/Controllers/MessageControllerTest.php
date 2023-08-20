<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Message;
use App\Models\MetaData;
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
        $metaData1 = MetaData::factory()->create();
        $metaData2 = MetaData::factory()->create();
        $this->assertDatabaseCount('messages', 0);
        $this->actingAs($user)->post(route('messages.store'), [
            'message' => 'Foo bar',
            'meta_data' => [
                $metaData1,
                $metaData2
            ]
        ]);
        $this->assertDatabaseCount('messages', 1);

        $message = Message::first();
        $this->assertCount(2, $message->meta_data);
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
