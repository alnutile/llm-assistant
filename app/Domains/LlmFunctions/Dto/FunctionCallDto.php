<?php

namespace App\Domains\LlmFunctions\Dto;

class FunctionCallDto extends \Spatie\LaravelData\Data
{

    public function __construct(
        public string $name,
        public array $content
    )
    {
    }
}
