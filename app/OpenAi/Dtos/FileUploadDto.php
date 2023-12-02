<?php

namespace App\OpenAi\Dtos;

use App\OpenAi\Enums\FileStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class FileUploadDto extends Data
{
    public function __construct(
        public string $id,
        public string $filename,
        #[WithCast(EnumCast::class, type: FileStatusEnum::class)]
        public FileStatusEnum $status,
    ) {
    }
}
