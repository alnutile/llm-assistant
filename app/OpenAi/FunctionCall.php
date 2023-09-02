<?php

namespace App\OpenAi;

use App\Models\LlmFunction;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto as OpenAiFunctionCall;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FunctionCall
{
    public function handle(string $function_name, OpenAiFunctionCall $callDto): Message
    {
        try {
            /**
             * @TODO
             * hmm I do this to prove it exists but I am not doing much else with it
             * maybe like Nova I make this class based
             * though I like the user can just add them in the UI?
             */
            LlmFunction::where('label', $function_name)->firstOrfail();

            /**
             * @TODO
             * Going to move this to AppServiceProvider
             * Right now they are in the helpers.php file
             */
            return $function_name($callDto);
        } catch (ModelNotFoundException $exception) {
            logger('The function does not exist '.$function_name);

            return $callDto->message;
        }
    }
}
