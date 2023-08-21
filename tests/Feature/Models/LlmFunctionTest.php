<?php

namespace Tests\Feature\Models;

use App\Models\LlmFunction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LlmFunctionTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory()
    {
        $model = LlmFunction::factory()->create();
        $this->assertNotNull($model->label);
    }
}
