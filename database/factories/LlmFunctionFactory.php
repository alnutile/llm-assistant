<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LlmFunction>
 */
class LlmFunctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->word,
            'description' => fake()->sentences(3, true),
            'parameters' => get_fixture('function_parameters.json'),
            'active' => fake()->boolean,
        ];
    }

    public function scheduleFunction()
    {
        return $this->state(function (array $attributes) {
            return [
                'label' => 'llm_functions_scheduling',
                'description' => 'Passing Month-Day-Year and Description the function will make a scheduled task',
                'parameters' => get_fixture('function_parameters.json'),
            ];
        });
    }
}
