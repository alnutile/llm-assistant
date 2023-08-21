<?php

namespace App\OpenAi;

use App\OpenAi\Dtos\Response;
use OpenAI\Laravel\Facades\OpenAI;

class ChatClient
{
    public function chat(array $message, bool $include_function = false): Response
    {
        if (config('openai.mock') && ! app()->environment('testing')) {
            logger('Mocking');
            sleep(2);
            $data = get_fixture('example_response.json');

            return Response::from($data);
        }

        $request = [
            'model' => config('openai.chat_model'),
            'messages' => $message,
            'temperature' => (int) config('openai.temperature'),
        ];

        if ($include_function) {
            $request['functions'] = $this->getFunctions();
        }

        $response = OpenAI::chat()->create($request);

        return Response::from(
            [
                'content' => data_get($response, 'choices.0.message.content'),
                'role' => data_get($response, 'choices.0.message.role'),
                'token_count' => $response->usage->totalTokens,
                'finish_reason' => data_get($response, 'choices.0.finish_reason'),
            ]
        );

    }

    protected function getFunctions(): array
    {

        return [
            [
                'name' => 'get_current_weather',
                'description' => 'Get the current weather in a given location',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'location' => [
                            'type' => 'string',
                            'description' => 'The city and state, e.g. San Francisco, CA',
                        ],
                        'unit' => [
                            'type' => 'string',
                            'enum' => ['celsius', 'fahrenheit'],
                        ],
                    ],
                    'required' => ['location'],
                ],
            ],
        ];
    }
}
