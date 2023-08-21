<?php

namespace Tests\Feature;

use App\Domains\EmailParser\MailDto;
use App\Jobs\MailBoxParserJob;
use App\Models\User;
use Tests\TestCase;

class MailBoxParserJobTest extends TestCase
{
    public function test_makes_message()
    {

        User::factory()->create();

        $dto = MailDto::from([
            'to' => 'info@llmassistant.io',
            'from' => 'foo@var.com',
            'subject' => 'This is it',
            'body' => 'Foo boo',
        ]);

        $this->assertDatabaseCount('messages', 0);
        $job = new MailBoxParserJob($dto);
        $job->handle();
        $this->assertDatabaseCount('messages', 1);

    }
}
