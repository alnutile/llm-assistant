<?php

namespace App\Spiders;

class ResponseDto extends \Spatie\LaravelData\Data
{
    public function __construct(
        public string $content)
    {
    }
}
