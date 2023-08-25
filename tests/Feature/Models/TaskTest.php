<?php

namespace Tests\Feature\Models;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory()
    {
        $model = Task::factory()->create();
        $this->assertNotNull($model->due);
        $this->assertNotNull($model->message->id);
        $this->assertNotNull($model->message->tasks->first()->id);
    }
}
