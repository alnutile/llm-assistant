<?php

namespace App\Domains\LlmFunctions\GetContentFromUrl;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Domains\LlmFunctions\LlmFunctionContract;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;
use Facades\App\Tools\GetSiteWrapper;

class GetContentFromUrl extends LlmFunctionContract
{
    public function handle(FunctionCallDto $functionCallDto): Message
    {
        $url = data_get($functionCallDto->arguments, 'url', null);

        if (! $url) {
            $message = 'No url in this message '.$functionCallDto->message->id;
            logger($message);
            logger('See function call', $functionCallDto->toArray());
            throw new \Exception($message);
        }

        $body = GetSiteWrapper::handle($url);

        return Message::create(
            [
                'parent_id' => $functionCallDto->message->id,
                'role' => RoleTypeEnum::Function,
                'user_id' => $functionCallDto->message->user_id,
                'content' => $body,
                'name' => $functionCallDto->function_name,
            ]
        );

    }
}
