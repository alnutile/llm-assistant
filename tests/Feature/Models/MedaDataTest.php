<?php

namespace Tests\Feature\Models;

use App\Models\MedaData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MedaDataTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_factory() {
        $model = MedaData::factory()->create();

        $this->assertNotNull($model->user->id);

        $this->assertNotNull($model->user->meta_data);
    }
}
