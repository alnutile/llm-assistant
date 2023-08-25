<?php

namespace App\OpenAi\Dtos;

use App\Models\Message;
use Spatie\LaravelData\Attributes\WithCast;

class FunctionCallDto extends \Spatie\LaravelData\Data
{
    public function __construct(
        #[WithCast(ArgumentCaster::class)]
        public array $arguments,
        public Message $message
    ) {
    }
}
