<?php

namespace App\OpenAi\Dtos;

use App\Models\Message;

class FunctionCallDto extends \Spatie\LaravelData\Data
{
    public function __construct(
        public array $arguments,
        public Message $message
    ) {
    }
}
