<?php

namespace App\OpenAi;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Models\LlmFunction;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;
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

    public function chat(array $messages, bool $run_functions = true): Message
    {

        if (config('openai.mock') && ! app()->environment('testing')) {
            logger('Mocking');
            sleep(2);
            $data = get_fixture('example_response.json');

            return Message::create([
                'user_id' => $this->messageModel->user_id,
                'content' => data_get($data, 'content'),
                'role' => data_get($data, 'role'),
                'parent_id' => $this->messageModel->id,
            ]);
        }

        if ($this->hasFunctions() && $run_functions) {
            $model = config('openai.chat_model_with_function');
        } else {
            $model = config('openai.chat_model');
        }

        logger('Model being used', ['model' => $model]);

        $request = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => (int) config('openai.temperature'),
        ];

        if ($this->hasFunctions() && $run_functions) {
            $function = $this->getFunctions();
            if (! empty($function)) {
                $request['functions'] = $function;
            }
        }

        $response = OpenAI::chat()->create($request);

        if (data_get($response, 'choices.0.finish_reason') === 'function_call') {
            $name = data_get($response, 'choices.0.message.function_call.name');
            $arguments = data_get($response, 'choices.0.message.function_call.arguments');
            $dto = FunctionCallDto::from(
                [
                    'arguments' => $arguments,
                    'function_name' => $name,
                    'message' => $this->messageModel,
                ]);

            logger('Making function call then will reiterate', [
                $arguments,
            ]);

            Message::create([
                'parent_id' => $this->messageModel->id,
                'role' => RoleTypeEnum::Assistant,
                'user_id' => $this->messageModel->user_id,
                'content' => null,
                'function_call' => \App\Domains\LlmFunctions\Dto\FunctionCallDto::from([
                    'name' => $name,
                    'content' => json_decode($arguments, true),
                ]),
            ]);

            return FunctionCall::handle($name, $dto);

        } else {
            return Message::create([
                'user_id' => $this->messageModel->user_id,
                'content' => data_get($response, 'choices.0.message.content'),
                'role' => data_get($response, 'choices.0.message.role'),
                'parent_id' => $this->messageModel->id,
            ]);
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
                'parameters' => $llm_functionModel->parameters_decoded,
            ];
        }

        return $llm_functions;
    }
}
