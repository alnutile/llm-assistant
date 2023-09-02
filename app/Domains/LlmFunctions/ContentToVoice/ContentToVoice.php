<?php

namespace App\Domains\LlmFunctions\ContentToVoice;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;
use Facades\App\Domains\LlmFunctions\ContentToVoice\ContentToVoiceClient;

class ContentToVoice extends \App\Domains\LlmFunctions\LlmFunctionContract
{
    public function handle(FunctionCallDto $functionCallDto): Message
    {
        $content = data_get($functionCallDto->arguments, 'content', null);

        if (! $content) {
            $message = 'No content in this message '.$functionCallDto->message->id;
            logger($message);
            logger('See function call', $functionCallDto->toArray());
            throw new \Exception($message);
        }

        $voiceUrl = ContentToVoiceClient::handle($content);

        return Message::create(
            [
                'parent_id' => $functionCallDto->message->id,
                'role' => RoleTypeEnum::Function,
                'user_id' => $functionCallDto->message->user_id,
                'content' => $voiceUrl, //@TODO download the file and host it somehow
                'name' => $functionCallDto->function_name,
            ]
        );
    }
}
