<?php

namespace Tests\Feature;

use App\Models\LlmFunction;
use App\OpenAi\Dtos\Response;
use Facades\App\OpenAi\ChatClient;
use Illuminate\Support\Arr;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;
use Tests\TestCase;

class ChatClientTest extends TestCase
{


    public function test_sends_functions()
    {
        $data = get_fixture('chat_message_response.json');
        OpenAI::fake([
            CreateResponse::fake([
                $data,
            ]),
        ]);

        $message = [
            [
                'role' => 'system',
                'content' => 'Foobar',
            ],
            [
                'role' => 'user',
                'content' => 'Foobar',
            ],
        ];

        LlmFunction::factory()->scheduleFunction()->create();
        $results = ChatClient::chat($message, ['llm_functions_scheduling']);
        $this->assertInstanceOf(Response::class, $results);
        $this->assertNotNull($results->content);
        $this->assertStringContainsString('Hello',
            $results->content);

        OpenAI::assertSent(\OpenAI\Resources\Chat::class, function(string $method, array $parameters) : bool {
            return $parameters['model'] === config("openai.chat_model_with_function") &&
                !empty($parameters['functions']) &&
                data_get($parameters, 'functions.0.name') === 'llm_functions_scheduling';
        });
    }

    public function test_calls_chat_api()
    {
        $data = get_fixture('chat_message_response.json');
        OpenAI::fake([
            CreateResponse::fake([
                $data,
            ]),
        ]);

        $message = [
            [
                'role' => 'system',
                'content' => 'Foobar',
            ],
            [
                'role' => 'user',
                'content' => 'Foobar',
            ],
        ];
        $results = ChatClient::chat($message);
        $this->assertInstanceOf(Response::class, $results);
        $this->assertNotNull($results->content);
        $this->assertStringContainsString('Hello',
            $results->content);
    }
}
