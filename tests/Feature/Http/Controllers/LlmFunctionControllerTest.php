<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\LlmFunction;
use App\Models\User;
use Tests\TestCase;

class LlmFunctionControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(
            route('llm_functions.index')
        )->assertStatus(200);
    }

    public function test_store(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseCount('llm_functions', 0);
        $this->actingAs($user)->post(
            route('llm_functions.store'),
            [
                'label' => 'Foobar',
                'content' => 'Foobar',
            ]
        )->assertStatus(302);
        $this->assertDatabaseCount('llm_functions', 1);
    }

    public function test_update(): void
    {
        $user = User::factory()->create();

        $model = LlmFunction::factory()->create();
        $this->assertDatabaseCount('llm_functions', 1);
        $this->actingAs($user)->put(
            route('llm_functions.update', [
                'llm_function' => $model->id,
            ]),
            [
                'label' => 'Foobar',
                'content' => 'Foobar',
            ]
        )->assertStatus(302);
        $this->assertDatabaseCount('llm_functions', 1);
        $this->assertEquals('Foobar', $model->refresh()->label);
        $this->assertEquals('Foobar', $model->refresh()->content);
    }
}
