<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Tag;
use Database\Seeders\AddFunctionTagArticle;
use Facades\App\Domains\LlmFunctions\TagArticle\TagArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_query(): void
    {
        $this->seed(AddFunctionTagArticle::class);

        $message = Message::factory()->create();
        /** @var Tag $tag */
        $tag = Tag::factory()->create();
        $dto = \App\OpenAi\Dtos\FunctionCallDto::from([
            'arguments' => json_encode([
                'article_id' => $message->id,
                'tags' => [
                    [
                        'id' => $tag->id,
                        'label' => $tag->label,
                    ],
                    [
                        'id' => null,
                        'label' => 'Some new tag',
                    ],
                ],
            ]),
            'function_name' => 'get_existing_tags',
            'message' => $message,
        ]);
        $this->assertDatabaseCount('tags', 1);

        $messageCreated = TagArticle::handle($dto);

        $this->assertDatabaseCount('tags', 2);

        $this->assertCount(2, $message->refresh()->tags);
    }

    public function test_helper(): void
    {
        $this->seed(AddFunctionTagArticle::class);

        $message = Message::factory()->create();
        /** @var Tag $tag */
        $tag = Tag::factory()->create();
        $dto = \App\OpenAi\Dtos\FunctionCallDto::from([
            'arguments' => json_encode([
                'article_id' => $message->id,
                'tags' => [
                    [
                        'id' => $tag->id,
                        'label' => $tag->label,
                    ],
                    [
                        'id' => null,
                        'label' => 'Some new tag',
                    ],
                ],
            ]),
            'function_name' => 'get_existing_tags',
            'message' => $message,
        ]);
        $this->assertDatabaseCount('tags', 1);

        $messageCreated = add_tags_to_article($dto);

        $this->assertDatabaseCount('tags', 2);

        $this->assertCount(2, $message->refresh()->tags);
    }
}
