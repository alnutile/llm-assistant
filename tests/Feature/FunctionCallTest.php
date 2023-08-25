<?php

namespace Tests\Feature;

use Facades\App\Domains\Scheduling\TaskRepository;
use App\Models\LlmFunction;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;
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
        $call = json_decode($call, true);
        $dto = FunctionCallDto::from([
            'arguments' => $call,
            'message' => $message
        ]);
        FunctionCall::handle('llm_functions_scheduling', $dto);
    }
}
