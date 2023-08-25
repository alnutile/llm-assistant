<?php

namespace Tests\Feature;

use App\Domains\Scheduling\Dtos\TasksDto;
use App\Models\LlmFunction;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;
use Facades\App\Domains\Scheduling\TaskRepository;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_makes_tasks(): void
    {
        $this->assertDatabaseCount('tasks', 0);
        $message = Message::factory()->create();
        $llm = LlmFunction::factory()->scheduleFunction()->create();
        $call = get_fixture('functions_response_with_function_call.json');

        $call = data_get($call, 'choices.0.message.function_call.arguments');
        $dto = FunctionCallDto::from([
            'arguments' => $call,
            'message' => $message,
        ]);
        TaskRepository::handle(TasksDto::from($dto->arguments), $dto->message);
        $this->assertDatabaseCount('tasks', 3);
    }
}
