<?php

namespace Database\Factories;

use App\Domains\LlmFunctions\Dto\FunctionCallDto;
use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
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
            'role' => RoleTypeEnum::User,
            'content' => fake()->sentence(4, true),
            'user_id' => User::factory(),
            'name' => 'get_current_weather',
            'parent_id' => null,
            'function_call' => FunctionCallDto::from([
                'name' => 'some_function',
                'content' => [
                    'foo' => 'bar',
                ],
            ]),
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
