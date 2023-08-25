<?php

namespace Tests\Feature\Http\Controllers;

use App\OpenAi\Dtos\Response;
use Facades\App\Domains\Message\MessageRepository;
use App\Jobs\MessageCreatedJob;
use App\Models\LlmFunction;
use App\Models\Message;
use App\Models\MetaData;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Event;
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
        $tag = Tag::factory()->create();
        $llm = LlmFunction::factory()->create();
        $this->actingAs($user)->post(route('messages.store'), [
            'content' => 'Foo bar',
            'llm_functions' => [
                $llm,
            ],
            'tags' => [
                $tag,
            ],
            'meta_data' => [
                $metaData1,
                $metaData2,
            ],
        ]);
        $this->assertDatabaseCount('messages', 1);

        $message = Message::first();
        $this->assertCount(2, $message->meta_data);
        $this->assertCount(1, $message->tags);
        $this->assertCount(1, $message->llm_functions);
        Queue::assertPushed(MessageCreatedJob::class);
    }


    public function test_rerun() {
        Event::fake();
        $dto = Response::from([
            'content' => "Foobar",
        ]);
        MessageRepository::shouldReceive('handle')->once()->andReturn($dto);
        $user = User::factory()->create();
        $message = Message::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->post(route('messages.rerun', [
            'message' => $message->id,
        ]));
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
            $metaData2->id,
        ]);

        $tag = Tag::factory()->create();

        $child = Message::factory()->create([
            'parent_id' => $message->id,
        ]);

        $this->actingAs($user)->put(route('messages.update', [
            'message' => $message->id,
        ]), [
            'content' => 'Foo bar',
            'tags' => [
                $tag,
            ],
            'meta_data' => [
                $metaData1,
            ],
        ]);

        $this->assertCount(1, $message->refresh()->meta_data);
        $this->assertCount(1, $message->refresh()->tags);
        $this->assertCount(0, $message->children);
        Queue::assertPushed(MessageCreatedJob::class);
    }
}
