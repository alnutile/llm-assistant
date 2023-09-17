<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Tag;
use App\OpenAi\Dtos\FunctionCallDto;
use Database\Seeders\GetExistingTagsSeeder;
use Facades\App\Domains\LlmFunctions\GetExistingTags\GetExistingTags;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetExistingTagsTest extends TestCase
{
    use RefreshDatabase;

    public function test_query()
    {
        $this->seed(GetExistingTagsSeeder::class);

        $message = Message::factory()->create();

        /** @var Tag $tag */
        $tag = Tag::factory()->create();
        $tag2 = Tag::factory()->create();
        $dto = FunctionCallDto::from([
            'arguments' => json_encode([
                'query' => 'SELECT id, label, active, created_at, updated_at FROM tags',
            ]),
            'function_name' => 'get_existing_tags',
            'message' => $message,
        ]);

        /** @var Message $messageCreated */
        $messageCreated = GetExistingTags::handle($dto);

        $this->assertStringContainsString($tag->label, $messageCreated->content);
    }
}
