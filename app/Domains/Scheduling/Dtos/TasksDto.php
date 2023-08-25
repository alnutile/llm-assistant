<?php

namespace App\Domains\Scheduling\Dtos;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class TasksDto extends Data
{

    /**
     * @param TaskDto[] $tasks
     */
    public function __construct(
        #[WithCast(TaskCaster::class)]
        public array $tasks = []
    )
    {
    }

}
