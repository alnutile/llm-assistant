<?php

namespace App\OpenAi;

use App\Models\LlmFunction;
use App\OpenAi\Dtos\Response;
use OpenAI\Contracts\ResponseContract;
use OpenAI\Laravel\Facades\OpenAI;

class ChatClient
{
    public function chat(array $message, array $included_function = []): Response
    {
        if (config('openai.mock') && ! app()->environment('testing')) {
            logger('Mocking');
            sleep(2);
            $data = get_fixture('example_response.json');

            return Response::from($data);
        }

        if (! empty($included_function)) {
            $model = config('openai.chat_model_with_function');
        } else {
            $model = config('openai.chat_model');
        }

        $request = [
            'model' => $model,
            'messages' => $message,
            'temperature' => (int) config('openai.temperature'),
        ];

        if (! empty($included_function)) {
            $request['functions'] = $this->getFunctions($included_function);
        }

        put_fixture('request_going_in.json', $request);

        /** @var ResponseContract $response */
        $response = OpenAI::chat()->create($request);

        put_fixture('functions_response.json', $response->toArray());
        logger('Message complete');

        return Response::from(
            [
                'content' => data_get($response, 'choices.0.message.content'),
                'role' => data_get($response, 'choices.0.message.role'),
                /** @phpstan-ignore-next-line */
                'token_count' => $response->usage->totalTokens,
                'finish_reason' => data_get($response, 'choices.0.finish_reason'),
            ]
        );

    }

    protected function getFunctions(array $included_function): array
    {
        $llm_functions = [];
        foreach ($included_function as $llm_function) {
            /** @var LlmFunction $llm_functionModel */
            $llm_functionModel = LlmFunction::where('label', 'LIKE', $llm_function)->first();

            if ($llm_functionModel != null) {
                $llm_functions[] = [
                    'name' => $llm_functionModel->label,
                    'description' => $llm_functionModel->description,
                    'parameters' => $llm_functionModel->parameters,
                ];
            }
        }

        return $llm_functions;
    }
}
