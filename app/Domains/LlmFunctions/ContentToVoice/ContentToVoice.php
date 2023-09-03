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

        if (config('services.rapid.mock') && ! app()->environment('testing')) {
            $voiceUrl = "https://s3.eu-central-1.amazonaws.com/tts-download/44e644bc33580c66bd33751beb941c54.wav?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAZ3CYNLHHVKA7D7Z4%2F20230902%2Feu-central-1%2Fs3%2Faws4_request&X-Amz-Date=20230902T185524Z&X-Amz-Expires=86400&X-Amz-SignedHeaders=host&X-Amz-Signature=f196f78591584911a09543f775ad581918d5e6bbb55d49e94e051f98a7f82aaf";
        } else {
            $voiceUrl = ContentToVoiceClient::handle($content);
        }


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
