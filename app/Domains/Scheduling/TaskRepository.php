<?php

namespace App\Domains\Scheduling;

use App\Domains\Scheduling\Dtos\TasksDto;
use App\Models\Message;
use App\Models\Task;
use Carbon\Carbon;

class TaskRepository
{
    public function handle(TasksDto $tasksDto, Message $message): void
    {
        foreach ($tasksDto->tasks as $task) {
            if (! Task::where('description', $task->description)->where('message_id', $message->id)->exists()) {
                Task::create([
                    'due' => ($task->date) ? Carbon::parse($task->date) : null,
                    'description' => $task->description,
                    'message_id' => $message->id,
                ]);
            }
        }

    }
}
