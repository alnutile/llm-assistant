<?php

namespace App\Domains\Scheduling;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Domains\LlmFunctions\LlmFunctionContract;
use App\Domains\Scheduling\Dtos\TasksDto;
use App\Models\Message;
use App\Models\Task;
use App\OpenAi\Dtos\FunctionCallDto;
use Carbon\Carbon;

class TaskRepository extends LlmFunctionContract
{
    public function handle(FunctionCallDto $functionCallDto): Message
    {
        $tasksDto = TasksDto::from($functionCallDto->arguments);
        $message = $functionCallDto->message;
        $summary = [];
        foreach ($tasksDto->tasks as $task) {
            if (! Task::where('description', $task->description)->where('message_id', $message->id)->exists()) {
                Task::create([
                    'due' => ($task->date) ? Carbon::parse($task->date) : null,
                    'description' => $task->description,
                    'message_id' => $message->id,
                ]);

                $summary[] = $task->description.' on '.$task->date;
            }
        }

        $summary = implode("\n", $summary);

        $summary = sprintf("The following tasks have been created %s\n\n", $summary);

        return Message::create(
            [
                'parent_id' => $functionCallDto->message->id,
                'role' => RoleTypeEnum::Function,
                'user_id' => $functionCallDto->message->user_id,
                'content' => $summary,
                'name' => $functionCallDto->function_name,
            ]
        );
    }
}
