<?php

namespace Tests\Feature;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
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
            'function_name' => 'some_function_name',
            'arguments' => json_encode([
                'url' => 'https://foo.bar',
            ]),
        ]);

        /** @var Message $message */
        $message = GetContentFromUrl::handle($dto);

        $this->assertEquals(RoleTypeEnum::Function, $message->role);
        $this->assertEquals('some_function_name', $message->name);
    }
}
