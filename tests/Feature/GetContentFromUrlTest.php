<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use App\OpenAi\Dtos\FunctionCallDto;
use Facades\App\Domains\LlmFunctions\GetContentFromUrl\GetContentFromUrl;
use Facades\App\Tools\GetSiteWrapper;
use Tests\TestCase;

class GetContentFromUrlTest extends TestCase
{
    public function test_get_content()
    {
        $text = get_fixture_v2('larger_text.txt', false);

        GetSiteWrapper::shouldReceive('handle')
            ->once()
            ->andReturn($text);

        User::factory()->create();

        $message = Message::factory()->create();

        $dto = FunctionCallDto::from([
            'message' => $message,
            'arguments' => json_encode([
                'url' => 'https://foo.bar',
            ]),
        ]);

        GetContentFromUrl::handle($dto);

        $this->assertStringContainsString('navigation', $message->refresh()->content);
    }
}
