<?php

namespace Tests\Feature;

use App\Domains\EmailParser\MailDto;
use App\Jobs\MailBoxParserJob;
use App\Models\User;
use App\OpenAi\Dtos\Response;
use Facades\App\OpenAi\ChatClient;
use Facades\App\Tools\GetSiteWrapper;
use Tests\TestCase;

class MailBoxParserJobTest extends TestCase
{


    public function test_larger_message()
    {
        $dto = Response::from([
            'content' => 'reduced content',
        ]);


        ChatClient::shouldReceive('chat')
            ->once()
            ->andReturn($dto);

        $text = get_fixture_v2("larger_text.txt", false);
        GetSiteWrapper::shouldReceive('handle')
            ->once()
            ->andReturn($text);

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

    }

    public function test_makes_message()
    {
        $dto = Response::from([
            'content' => 'Foobar',
        ]);

        ChatClient::shouldReceive('chat')
            ->once()
            ->andReturn($dto);

        GetSiteWrapper::shouldReceive('handle')
            ->once()
            ->andReturn('Foo bar baz');

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

    }

    public function test_no_url()
    {
        $dto = Response::from([
            'content' => 'Foobar',
        ]);

        ChatClient::shouldReceive('chat')
            ->never();

        GetSiteWrapper::shouldReceive('handle')
            ->never();

        User::factory()->create();

        $dto = MailDto::from([
            'to' => 'info@llmassistant.io',
            'from' => 'foo@var.com',
            'subject' => 'This is it',
            'body' => 'not url',
        ]);

        $this->assertDatabaseCount('messages', 0);
        $job = new MailBoxParserJob($dto);
        $job->handle();
        $this->assertDatabaseCount('messages', 1);

    }
}
