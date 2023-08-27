<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    use HasEmbedDataTrait;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role' => 'user', //user, agent
            'content' => fake()->sentence(4, true),
            'user_id' => User::factory(),
            'parent_id' => null,
            'run_functions' => true,
        ];
    }

    public function suspended()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => Message::factory(),
            ];
        });
    }

    public function assistantMessage()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'assistant',
            ];
        });
    }

    public function systemMessage()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'system',
            ];
        });
    }
}
