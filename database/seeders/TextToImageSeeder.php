<?php

namespace Database\Seeders;

use App\Models\LlmFunction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TextToImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parameters = get_fixture_v2('text_for_image.json', false);
        LlmFunction::create([
            'label' => 'text_to_image',
            'description' => 'Use this function to convert and image to text',
            'parameters' => $parameters,
        ]);
    }
}
