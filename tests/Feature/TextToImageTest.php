<?php

namespace Tests\Feature;

use Facades\App\Domains\LlmFunctions\TextToImage\TextToImage;
use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Images\CreateResponse;
use Tests\TestCase;

class TextToImageTest extends TestCase
{

    public function test_image() {
        $image = get_fixture("image_response.json");
        OpenAI::fake([
            CreateResponse::fake([
                $image,
            ]),
        ]);

        $dto = FunctionCallDto::from([
            'function_name' => 'text_to_image',
            'message' => Message::factory()->create(),
            'arguments' => json_encode(['text_for_image' => "foo bar"])
        ]);

        $results = TextToImage::handle($dto);

        $this->assertEquals("https://openai.com/fake-image.png", $results->content);
    }
}
