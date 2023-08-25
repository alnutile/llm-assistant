<?php

namespace App\OpenAi\Dtos;

use Spatie\LaravelData\Support\DataProperty;

class ArgumentCaster implements \Spatie\LaravelData\Casts\Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        return json_decode($value, true);
    }
}
