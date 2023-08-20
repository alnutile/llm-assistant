<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index() {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route("dashboard"))
            ->assertStatus(200);
    }
}
