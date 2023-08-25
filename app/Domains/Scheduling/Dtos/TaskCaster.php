<?php

namespace App\Domains\Scheduling\Dtos;

use Spatie\LaravelData\Support\DataProperty;

class TaskCaster implements \Spatie\LaravelData\Casts\Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        $tasks = [];
        foreach ($value as $task) {
            $tasks[] = TaskDto::from($task);
        }

        return $tasks;
    }
}
