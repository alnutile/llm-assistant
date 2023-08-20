<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\MetaData;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MetaDataControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_metadata(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(
            route("meta_data.index")
        )->assertStatus(200);
    }

    public function test_store(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseCount("meta_data", 0);
        $this->actingAs($user)->post(
            route("meta_data.store"),
            [
                'label' => "Foobar",
                'content' => "Foobar",
            ]
        )->assertStatus(302);
        $this->assertDatabaseCount("meta_data", 1);
    }

    public function test_update(): void
    {
        $user = User::factory()->create();

        $meta_data = MetaData::factory()->create();
        $this->assertDatabaseCount("meta_data", 1);
        $this->actingAs($user)->put(
            route("meta_data.update", [
                'meta_data' => $meta_data->id
            ]),
            [
                'label' => "Foobar",
                'content' => "Foobar",
            ]
        )->assertStatus(302);
        $this->assertDatabaseCount("meta_data", 1);
        $this->assertEquals("Foobar", $meta_data->refresh()->label);
        $this->assertEquals("Foobar", $meta_data->refresh()->content);
    }

}
