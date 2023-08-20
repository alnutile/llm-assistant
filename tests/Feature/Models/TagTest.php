<?php

namespace Tests\Feature\Models;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{

    use RefreshDatabase;

    public function test_factory() {
        $model = Tag::factory()->create();
        $this->assertNotNull($model->label);
    }
}
