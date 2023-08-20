<?php

namespace Tests\Feature\Http\Controllers;

use App\Jobs\MessageCreatedJob;
use App\Models\Message;
use App\Models\MetaData;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
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
        Queue::fake();
        $user = User::factory()->create();
        $metaData1 = MetaData::factory()->create();
        $metaData2 = MetaData::factory()->create();
        $this->assertDatabaseCount('messages', 0);
        $this->actingAs($user)->post(route('messages.store'), [
            'content' => 'Foo bar',
            'meta_data' => [
                $metaData1,
                $metaData2,
            ],
        ]);
        $this->assertDatabaseCount('messages', 1);

        $message = Message::first();
        $this->assertCount(2, $message->meta_data);
        Queue::assertPushed(MessageCreatedJob::class);
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

    public function test_startOver()
    {
        Queue::fake();
        $user = User::factory()->create();
        $message = Message::factory()->create();
        $metaData1 = MetaData::factory()->create();
        $metaData2 = MetaData::factory()->create();
        $message->meta_data()->attach([
            $metaData1->id,
            $metaData2->id
        ]);

        $child = Message::factory()->create([
            'parent_id' => $message->id
        ]);



        $this->actingAs($user)->put(route('messages.update', [
            'message' => $message->id
        ]), [
            'content' => 'Foo bar',
            'meta_data' => [
                $metaData1,
            ],
        ]);

        $this->assertCount(1, $message->refresh()->meta_data);
        $this->assertCount(0, $message->children);
        Queue::assertPushed(MessageCreatedJob::class);
    }
}
