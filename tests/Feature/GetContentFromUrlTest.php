<?php

namespace Tests\Feature;

use App\OpenAi\Dtos\FunctionCallDto;
use Facades\App\Domains\LlmFunctions\GetContentFromUrl\GetContentFromUrl;
use App\Models\Message;
use App\Models\User;
use Facades\App\Tools\GetSiteWrapper;
use Facades\App\OpenAi\ChatClient;
use App\OpenAi\Dtos\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetContentFromUrlTest extends TestCase
{

    public function test_get_content() {
        $dto = Response::from([
            'content' => 'reduced content',
        ]);

        ChatClient::shouldReceive('chat')
            ->once()
            ->andReturn($dto);

        $text = get_fixture_v2('larger_text.txt', false);
        GetSiteWrapper::shouldReceive('handle')
            ->once()
            ->andReturn($text);

        User::factory()->create();

        $message = Message::factory()->create();

        $dto = FunctionCallDto::from([
           'message' => $message,
           'arguments' => json_encode([
               'url' => "https://foo.bar"
           ])
        ]);
        GetContentFromUrl::handle($dto);

        $this->assertStringContainsString('reduced content', $message->refresh()->content);
    }
}
