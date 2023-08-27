<?php

namespace App\Domains\LlmFunctions\GetContentFromUrl;

use App\Models\Message;
use Facades\App\OpenAi\ChatClient;
use App\OpenAi\Dtos\FunctionCallDto;
use Facades\App\Tools\GetSiteWrapper;
use SundanceSolutions\LarachainTrimText\Facades\LarachainTrimText;

class GetContentFromUrl
{

    public function handle(FunctionCallDto $functionCallDto) : Message {
            $url = data_get($functionCallDto->arguments, 'url', null);

            if(!$url) {
                $message = "No url in this message " . $functionCallDto->message->id;
                logger($message);
                logger("See function call", $functionCallDto->toArray());
                throw new \Exception($message);
            }

            $body = GetSiteWrapper::handle($url);

            $content = sprintf("can you add a TLDR to the top of the following content:
            ###
            \n
            \n
            \n
                    URL: %s\n
                    Content: %s",
                $url,
                $body
            );

            $functionCallDto->message->content = $content;
            $functionCallDto->message->updateQuietly();
            return $functionCallDto->message->refresh();

    }


}
