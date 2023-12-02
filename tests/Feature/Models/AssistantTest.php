<?php

namespace Tests\Feature\Models;

use App\Models\Assistant;
use App\Models\Message;
use Tests\TestCase;

class AssistantTest extends TestCase
{

    public function test_model() {
        $model = Assistant::factory()->create();

        $this->assertNotNull($model->created_by->id);
        $this->assertNotNull($model->assistantable->id);

        $this->assertInstanceOf(Message::class, $model->assistantable);

        $this->assertCount(1, $model->assistantable->assistants);
        $this->assertCount(1, $model->assistantable->bullet_journal_assistants);
    }
}
