<?php

namespace Tests\Feature;

use App\Models\LlmFunction;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;
use Facades\App\Domains\Scheduling\TaskRepository;
use Facades\App\OpenAi\FunctionCall;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FunctionCallTest extends TestCase
{
    use RefreshDatabase;

    public function test_calls_for_scheduling()
    {
        TaskRepository::shouldReceive('handle')->once();
        $message = Message::factory()->create();
        $llm = LlmFunction::factory()->scheduleFunction()->create();
        $call = get_fixture('functions_response_with_function_call.json');

        $call = data_get($call, 'choices.0.message.function_call.arguments');
        $dto = FunctionCallDto::from([
            'function_name' => 'llm_functions_scheduling',
            'arguments' => $call,
            'message' => $message,
        ]);
        FunctionCall::handle('llm_functions_scheduling', $dto);
    }

    public function test_makes_messages()
    {
        TaskRepository::shouldReceive('handle')->once();
        $message = Message::factory()->create();
        $llm = LlmFunction::factory()->scheduleFunction()->create();
        $call = get_fixture('functions_response_with_function_call.json');

        $call = data_get($call, 'choices.0.message.function_call.arguments');
        $dto = FunctionCallDto::from([
            'function_name' => 'llm_functions_scheduling',
            'arguments' => $call,
            'message' => $message,
        ]);
        FunctionCall::handle('llm_functions_scheduling', $dto);
    }
}
