<?php

namespace App\OpenAi\Dtos;

class Response extends \Spatie\LaravelData\Data
{
    public function __construct(
        public mixed $content,
        public int $token_count = 0,
        public string $finish_reason = 'stop',
        public string $role = 'assistant'
    ) {

    }
}
