<?php

namespace Tests\Feature;

use App\Domains\EmailParser\MailDto;
use App\Jobs\MailBoxParserJob;
use App\Jobs\MessageCreatedJob;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MailBoxParserJobTest extends TestCase
{
    public function test_larger_message()
    {
        Queue::fake();
        User::factory()->create();

        $dto = MailDto::from([
            'to' => 'info@llmassistant.io',
            'from' => 'foo@var.com',
            'subject' => 'This is it',
            'body' => 'https://foo.com',
        ]);

        $this->assertDatabaseCount('messages', 0);
        $job = new MailBoxParserJob($dto);
        $job->handle();
        $this->assertDatabaseCount('messages', 1);
        $message = Message::first();
        $this->assertCount(3, $message->llm_functions);
        Queue::assertPushed(MessageCreatedJob::class);
    }
}
