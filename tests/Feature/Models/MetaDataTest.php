<?php

namespace Tests\Feature\Models;

use App\Models\MetaData;
use Tests\TestCase;

class MetaDataTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_factory()
    {
        $model = MetaData::factory()->create();

        $this->assertNotNull($model->user->id);

        $this->assertNotNull($model->user->meta_data);
    }
}
