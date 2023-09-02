<?php

namespace App\Domains\LlmFunctions;

use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;

abstract class LlmFunctionContract
{
    abstract public function handle(FunctionCallDto $functionCallDto): Message;
}
