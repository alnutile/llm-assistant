<?php

namespace App\OpenAi\Dtos;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;

class MessageDto extends \Spatie\LaravelData\Data
{
    public function __construct(
        public RoleTypeEnum|string $role,
        public ?string $content,
        public ?string $function,
        public ?string $name
    ) {
    }

    public function toArray(): array
    {
        $results = [
            'role' => $this->role,
            'content' => $this->content,
        ];

        if ($this->role instanceof RoleTypeEnum) {
            $results['role'] = $this->role->value;
        }

        if ($this->function) {
            $results['function'] = $this->function;
        }

        if ($this->name) {
            $results['name'] = $this->name;
        }

        return $results;
    }
}
