<?php

namespace Tests\Feature\Models;

use App\Models\LlmFunction;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LlmFunctionTest extends TestCase
{
    public function test_factory()
    {
        $model = LlmFunction::factory()->create();
        $this->assertNotNull($model->label);
    }

    public function test_rels()
    {
        $llm = LlmFunction::factory()->create();
        $message = Message::factory()->create();
        $message->llm_functions()->attach($llm->id);
        $this->assertCount(1, $message->llm_functions);
        $this->assertCount(1, $message->llm_functions->first()->messages);
    }

    public function test_creates_scheduler() {
        $llm = LlmFunction::factory()->scheduleFunction()->create();
        $this->assertNotNull($llm->parameters);
    }
}
