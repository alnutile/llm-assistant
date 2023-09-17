<?php

namespace App\Domains\LlmFunctions\TagArticle;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Domains\LlmFunctions\LlmFunctionContract;
use App\Models\Message;
use App\Models\Tag;
use App\OpenAi\Dtos\FunctionCallDto;

class TagArticle extends LlmFunctionContract
{
    public function handle(FunctionCallDto $functionCallDto): Message
    {

        $existingTags = [];
        $newTags = [];

        $message = $functionCallDto->message;

        foreach (data_get($functionCallDto->arguments, 'tags', []) as $tag) {
            if ($tag_id = data_get($tag, 'id')) {
                $tagModel = Tag::findOrFail($tag_id);
                $existingTags[] = $tagModel->label;
            } else {
                $tagModel = Tag::create([
                    'label' => data_get($tag, 'label'),
                    'active' => true,
                ]);
                $newTags[] = $tagModel->label;
            }

            $message->tags()->attach([
                $tagModel->id,
            ]);
        }

        $content = sprintf('Existing Tags added %s and new tags added %s',
            implode(',', $existingTags),
            implode(',', $newTags)
        );

        return Message::create(
            [
                'parent_id' => $functionCallDto->message->id,
                'role' => RoleTypeEnum::Function,
                'user_id' => $functionCallDto->message->user_id,
                'content' => $content,
                'name' => $functionCallDto->function_name,
            ]
        );
    }
}
