<?php

namespace App\OpenAi;

use App\Models\LlmFunction;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;
use App\OpenAi\Dtos\Response;
use Facades\App\OpenAi\ChatClient as ChatClientFacade;
use Facades\App\OpenAi\FunctionCall;
use OpenAI\Laravel\Facades\OpenAI;

class ChatClient
{
    public ?Message $messageModel = null;

    public function setMessage(Message $message): self
    {
        $this->messageModel = $message;

        return $this;
    }

    public function chat(array $messages): Response
    {
        if (config('openai.mock') && ! app()->environment('testing')) {
            logger('Mocking');
            sleep(2);
            $data = get_fixture('example_response.json');

            return Response::from($data);
        }

        if ($this->hasFunctions()) {
            $model = config('openai.chat_model_with_function');
        } else {
            $model = config('openai.chat_model');
        }

        $request = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => (int) config('openai.temperature'),
        ];

        if ($this->hasFunctions()) {
            $request['functions'] = $this->getFunctions();
        }

        $response = OpenAI::chat()->create($request);

        if (data_get($response, 'choices.0.finish_reason') === 'function_call') {
            $name = data_get($response, 'choices.0.message.function_call.name');
            $arguments = data_get($response, 'choices.0.message.function_call.arguments');
            $dto = FunctionCallDto::from([
                'arguments' => $arguments,
                'message' => $this->messageModel,
            ]);

            logger('Making function call then will reiterate', [
                $arguments,
            ]);

            FunctionCall::handle($name, $dto);

            $messages[] = [
                'role' => 'assistant',
                'content' => sprintf('As an assistant I ran the function %s for you with these parameters %s',
                    $name,
                    json_encode($dto->arguments)
                ),
            ];

            return ChatClientFacade::chat($messages);
        } else {
            return Response::from(
                [
                    'content' => data_get($response, 'choices.0.message.content'),
                    'role' => data_get($response, 'choices.0.message.role'),
                    'token_count' => $response->usage->totalTokens,
                    'finish_reason' => data_get($response, 'choices.0.finish_reason'),
                ]
            );
        }
    }

    protected function hasFunctions(): bool
    {
        return $this->messageModel && ! empty($this->messageModel->llm_functions);
    }

    protected function getFunctions(): array
    {
        $llm_functions = [];
        /** @var LlmFunction $llm_functionModel */
        foreach ($this->messageModel->llm_functions as $llm_functionModel) {
            $llm_functions[] = [
                'name' => $llm_functionModel->label,
                'description' => $llm_functionModel->description,
                'parameters' => $llm_functionModel->parameters,
            ];
        }

        return $llm_functions;
    }
}
