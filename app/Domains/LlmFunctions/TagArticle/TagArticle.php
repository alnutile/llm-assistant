<?php

namespace App\Domains\LlmFunctions\TagArticle;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Domains\LlmFunctions\LlmFunctionContract;
use Facades\App\Domains\Tagging\TagModelDescription;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;

class TagArticle extends LlmFunctionContract
{


    public function handle(FunctionCallDto $functionCallDto): Message
    {
        /**
         * @NOTE
         * For now I assume it is the tags table
         * I can do more later like all or Todos etc
         */
        $tags = TagModelDescription::getTagsTableInfo();

        return Message::create(
            [
                'parent_id' => $functionCallDto->message->id,
                'role' => RoleTypeEnum::Function,
                'user_id' => $functionCallDto->message->user_id,
                'content' => $tags,
                'name' => $functionCallDto->function_name,
            ]
        );
    }
}
