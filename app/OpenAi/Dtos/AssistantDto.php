<?php

namespace App\OpenAi\Dtos;

use Spatie\LaravelData\Data;

class AssistantDto extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $model,
        public string $instructions,
        public array $tools,
        public array $file_ids,
        public array $metadata,
    ) {
    }
}
