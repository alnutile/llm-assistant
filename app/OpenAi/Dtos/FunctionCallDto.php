<?php

namespace App\OpenAi\Dtos;

class FunctionCallDto extends \Spatie\LaravelData\Data
{

    public function __construct(
        public array $arguments
    )
    {
    }
}
