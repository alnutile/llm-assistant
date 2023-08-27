<?php

namespace App\Domains\Scheduling;

use App\Domains\Scheduling\Dtos\TasksDto;
use App\Jobs\MessageCreatedJob;
use App\Models\Message;
use App\Models\Task;
use Carbon\Carbon;

class TaskRepository
{
    public function handle(TasksDto $tasksDto, Message $message): void
    {
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

        $message->content = str($message->content)->prepend($summary);
        //        $message->run_functions = false;//prevent a loop
        $message->updateQuietly();
        //        MessageCreatedJob::dispatchSync($message);
    }
}
