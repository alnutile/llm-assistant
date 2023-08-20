<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    public function test_create()
    {
        $user = User::factory()->create();

        $this->assertDatabaseCount('tags', 0);
        $this->actingAs($user)->post(route('tags.store'), [
            'label' => 'Foobar',
        ])->assertStatus(302);
        $this->assertDatabaseCount('tags', 1);
    }
}
