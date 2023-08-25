<?php

namespace App\OpenAi;

use App\Models\LlmFunction;
use App\OpenAi\Dtos\FunctionCallDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FunctionCall
{

    public function handle(string $function_name, FunctionCallDto $callDto) {
            try {
                $function = LlmFunction::where('label', $function_name)->firstOrfail();
                $results = $function_name($callDto->arguments);

            } catch (ModelNotFoundException $exception) {
                logger("The function does not exist " . $function_name);
            }
    }
}
