<?php

namespace App\OpenAi;

use App\OpenAi\Dtos\Response;
use OpenAI\Laravel\Facades\OpenAI;

class ChatClient
{
    public function chat(array $message): Response
    {
        if (config('openai.mock') && ! app()->environment('testing')) {
            logger('Mocking');
            sleep(2);
            $data = get_fixture('example_response.json');

            return Response::from($data);
        }

        $results = OpenAI::chat()->create(
            [
                'model' => config('openai.chat_model'),
                'messages' => $message,
                'temperature' => (int) config('openai.temperature'),
            ]);

        return Response::from(
            [
                'content' => data_get($results, 'choices.0.message.content'),
                'role' => data_get($results, 'choices.0.message.role'),
                'token_count' => $results->usage->totalTokens,
                'finish_reason' => data_get($results, 'choices.0.finish_reason'),
            ]
        );

    }
}
