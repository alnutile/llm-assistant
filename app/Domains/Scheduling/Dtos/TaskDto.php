<?php

namespace App\Domains\Scheduling\Dtos;

class TaskDto extends \Spatie\LaravelData\Data
{

    public function __construct(
        public ?string $date,
        public string $description,
    )
    {
    }
}
