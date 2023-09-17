<?php

namespace Tests\Feature\Jobs;

use App\Events\MessageStatusEvent;
use App\Jobs\ReplyMessageCreateJob;
use App\Models\Message;
use App\OpenAi\Dtos\Response;
use Facades\App\Domains\Message\MessageReplyRepository;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ReplyMessageCreateJobTest extends TestCase
{
    public function test_reply_job()
    {
        Event::fake();
        $dto = Response::from([
            'content' => 'foobar',
            'role' => 'assistant',
            'token_count' => 100,
            'finish_reason' => 'stop',
        ]);

        $message = Message::factory()->create([
            'parent_id' => null,
        ]);

        $child = Message::factory()->create([
            'parent_id' => $message->id,
        ]);

        MessageReplyRepository::shouldReceive('handle')
            ->once()
            ->andReturn($message);

        $this->assertDatabaseCount('messages', 2);
        $job = new ReplyMessageCreateJob($child);
        $job->handle();
        Event::assertDispatched(MessageStatusEvent::class);
    }
}
