<?php

namespace Tests\Feature\Models;

use App\Models\Message;
use App\Models\MetaData;
use App\Models\Tag;
use Tests\TestCase;

class MessageTest extends TestCase
{
    public function test_message_factory()
    {
        $model = Message::factory()->withEmbedData()->create();
        $this->assertNotNull($model->user->id);
        $this->assertNotNull($model->embedding);
        $this->assertNotNull($model->user->messages->first()->id);
    }

    public function test_child_parent()
    {
        $parent = Message::factory()->create();

        $child = Message::factory()->create([
            'parent_id' => $parent->id,
        ]);

        $this->assertNotNull($child->parent->id);
        $this->assertEquals($parent->id, $child->parent->id);
    }

    public function test_parent_only()
    {
        $parent = Message::factory()->create();

        Message::factory()->count(2)->create([
            'parent_id' => $parent->id,
        ]);

        $this->assertCount(2, $parent->children);
    }

    public function test_meta_data()
    {
        $message = Message::factory()->create();
        $metaData = MetaData::factory()->create();

        $message->meta_data()->attach($metaData->id);
        $this->assertCount(1, $message->refresh()->meta_data);
    }

    public function test_tags()
    {
        $message = Message::factory()->create();
        $tag = Tag::factory()->create();

        $message->tags()->attach($tag->id);
        $this->assertCount(1, $message->refresh()->tags);
        $this->assertCount(1, $tag->refresh()->messages);
    }
}
