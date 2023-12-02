<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assistant>
 */
class AssistantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assistantable_id' => Message::factory(),
            'assistantable_type' => Message::class,
            'created_by_id' => User::factory(),
            'external_assistant_id' => "testing",
            'external_thread_id' => "bazboo"
        ];
    }
}
