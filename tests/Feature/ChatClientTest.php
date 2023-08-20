<?php

namespace Tests\Feature;

use App\OpenAi\Dtos\Response;
use Facades\App\OpenAi\ChatClient;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;
use Tests\TestCase;

class ChatClientTest extends TestCase
{
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
