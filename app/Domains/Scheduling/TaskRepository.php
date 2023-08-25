<?php

namespace App\Domains\Scheduling;

use App\Domains\Scheduling\Dtos\TaskDto;
use App\Domains\Scheduling\Dtos\TasksDto;
use App\Models\Message;
use App\Models\Task;
use Carbon\Carbon;

class TaskRepository
{
    public function handle(TasksDto $tasksDto, Message $message): void
    {
        /** @var TaskDto $task */
        foreach ($tasksDto->tasks as $task) {
            Task::create([
                'due' => ($task->date) ? Carbon::parse($task->date) : null,
                'description' => $task->description,
                'message_id' => $message->id,
            ]);
        }

    }
}
