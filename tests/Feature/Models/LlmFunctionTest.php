<?php

namespace Tests\Feature\Models;

use App\Models\LlmFunction;
use App\Models\Message;
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

    public function test_creates_scheduler()
    {
        $llm = LlmFunction::factory()->scheduleFunction()->create();
        $this->assertNotNull($llm->parameters);
    }

    public function test_exists_get_content_from_url() {
        $this->assertTrue(LlmFunction::where("label", 'get_content_from_url')->exists());
        /** @var LlmFunction $getContent */
        $getContent = LlmFunction::where("label", 'get_content_from_url')->first();
        $this->assertIsArray($getContent->parameters_decoded);
    }
}
