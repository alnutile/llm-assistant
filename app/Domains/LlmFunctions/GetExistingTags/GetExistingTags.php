<?php

namespace App\Domains\LlmFunctions\GetExistingTags;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Domains\LlmFunctions\LlmFunctionContract;
use Facades\App\Domains\Tagging\TagModelDescription;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;
use Illuminate\Support\Facades\DB;

class GetExistingTags extends LlmFunctionContract
{


    public function handle(FunctionCallDto $functionCallDto): Message
    {

        /**
         * @NOTE
         * Alarm bells should go off here.
         * Anytime we pass a raw query anything can go wrong
         * But this is to show how to use the Query Function
         * and the LlmAssistant is designed for one user only
         */
        $results = DB::select($functionCallDto->arguments['query']);

        $results = collect($results)->map(function($item) {
           return (array) $item;
        })->map(function ($item){
            $order = array_keys($item);
            $item = collect($item)->only($order)->toArray();
            return collect($item)->map(function ($value, $key) {
                return "{$key}: {$value}";
            })->implode(', ');
        })->implode("\n");


        $message = sprintf("Results of the query of tags table %s %s",
            $functionCallDto->arguments['query'],
            $results
        );

        return Message::create(
            [
                'parent_id' => $functionCallDto->message->id,
                'role' => RoleTypeEnum::Function,
                'user_id' => $functionCallDto->message->user_id,
                'content' => $message,
                'name' => $functionCallDto->function_name,
            ]
        );
    }
}
