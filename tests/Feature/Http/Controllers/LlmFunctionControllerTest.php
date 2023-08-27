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

        $this->actingAs($user)->post(
            route('llm_functions.store'),
            [
                'label' => 'Foobar',
                'description' => 'Foobar',
                'parameters' => ['foo' => 'bar'],
            ]
        )->assertStatus(302);
        $this->assertTrue(LlmFunction::whereLabel("Foobar")->exists());
    }

    public function test_update(): void
    {
        $user = User::factory()->create();

        $model = LlmFunction::factory()->create();
        $this->actingAs($user)->put(
            route('llm_functions.update', [
                'llm_function' => $model->id,
            ]),
            [
                'label' => 'Foobar',
                'description' => 'Foobar',
                'parameters' => ['foo' => 'bar'],
            ]
        )->assertStatus(302);
        $this->assertEquals('Foobar', $model->refresh()->label);
        $this->assertEquals(['foo' => 'bar'], $model->refresh()->parameters);
    }
}
