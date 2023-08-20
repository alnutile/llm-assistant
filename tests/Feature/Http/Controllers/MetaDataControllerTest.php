<?php

namespace Tests\Feature\Http\Controllers;

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
}
