<?php

namespace App\OpenAi\Dtos;

class MessageDto extends \Spatie\LaravelData\Data
{
    public function __construct(
        public string $role,
        public string $content
    ) {
    }
}
