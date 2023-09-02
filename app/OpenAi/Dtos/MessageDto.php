<?php

namespace App\OpenAi\Dtos;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;

class MessageDto extends \Spatie\LaravelData\Data
{
    public function __construct(
        public RoleTypeEnum|string $role,
        public string $content
    ) {
    }
}
