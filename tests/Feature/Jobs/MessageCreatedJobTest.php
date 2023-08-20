<?php

namespace Tests\Feature\Jobs;

use App\Jobs\MessageCreatedJob;
use App\Models\Message;
use App\OpenAi\Dtos\Response;
use Facades\App\Domains\Message\MessageRepository;
use Tests\TestCase;

class MessageCreatedJobTest extends TestCase
{

    public function test_makes_message() {
        $dto = Response::from([
            'content' => "foobar",
            'role' => "assistant",
            'token_count' => 100,
            'finish_reason' => "stop",
        ]);

        MessageRepository::shouldReceive('handle')
            ->once()
            ->andReturn($dto);

        $message = Message::factory()->create();

        $this->assertDatabaseCount("messages", 1);
        $job = new MessageCreatedJob($message);
        $job->handle();
        $this->assertDatabaseCount("messages", 2);
        $child = $message->children->first();
        $this->assertEquals("foobar", $child->content);
    }
}
