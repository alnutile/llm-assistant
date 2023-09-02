<?php

namespace Tests\Feature;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use Facades\App\Domains\LlmFunctions\ContentToVoice\ContentToVoice;
use App\Models\Message;
use App\Models\User;
use App\OpenAi\Dtos\FunctionCallDto;
use Facades\App\Domains\LlmFunctions\ContentToVoice\ContentToVoiceClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContentToVoiceTest extends TestCase
{

    public function test_gets_content() {
        ContentToVoiceClient::shouldReceive("handle")
            ->once()
            ->andReturn("Some url");

        User::factory()->create();

        $message = Message::factory()->create();

        $dto = FunctionCallDto::from([
            'message' => $message,
            'function_name' => 'some_function_name',
            'arguments' => json_encode([
                'content' => 'Foo bar',
            ]),
        ]);

        $message = ContentToVoice::handle($dto);
        $this->assertEquals(RoleTypeEnum::Function, $message->role);
        $this->assertEquals('some_function_name', $message->name);
    }
}
