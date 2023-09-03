<?php

namespace App\Domains\LlmFunctions\TextToImage;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Domains\LlmFunctions\LlmFunctionContract;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;
use OpenAI\Laravel\Facades\OpenAI;

class TextToImage extends LlmFunctionContract
{
    public function handle(FunctionCallDto $functionCallDto): Message
    {
        $text_to_image = data_get($functionCallDto->arguments, 'text_for_image', null);

        if (! $text_to_image) {
            $message = 'No text_to_image in this message '.$functionCallDto->message->id;
            logger($message);
            logger('See function call', $functionCallDto->toArray());
            throw new \Exception($message);
        }

        $text_to_image = sprintf('Using a watercolor style create %s', $text_to_image);

        if (config('openai.mock') && ! app()->environment('testing')) {
            logger('Mocking');
            sleep(2);

            return Message::create([
                'parent_id' => $functionCallDto->message->id,
                'role' => RoleTypeEnum::Function,
                'user_id' => $functionCallDto->message->user_id,
                'content' => 'https://oaidalleapiprodscus.blob.core.windows.net/private/org-ClL1biAi0m1pC2J2IV5C22TQ/user-i08oJb4T3Lhnsh2yJsoErWJ4/img-9oIErmWGtZIcdoT0wSlIlWAd.png?st=2023-09-02T18%3A00%3A44Z&se=2023-09-02T20%3A00%3A44Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2023-09-02T06%3A17%3A10Z&ske=2023-09-03T06%3A17%3A10Z&sks=b&skv=2021-08-06&sig=v/Bya8tpe2nGI%2B7ABNnSBzJvunHQWzz45T0TuwLlXzY%3D',
                'name' => $functionCallDto->function_name,
            ]);
        }

        $response = OpenAI::images()->create([
            'prompt' => $text_to_image,
            'n' => 1,
            'size' => '1024x1024',
            'response_format' => 'url',
        ]);

        $url = data_get($response, 'data.0.url');

        return Message::create(
            [
                'parent_id' => $functionCallDto->message->id,
                'role' => RoleTypeEnum::Function,
                'user_id' => $functionCallDto->message->user_id,
                'content' => $url,
                'name' => $functionCallDto->function_name,
            ]
        );

    }
}
