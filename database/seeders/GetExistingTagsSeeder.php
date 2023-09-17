<?php

namespace Database\Seeders;

use App\Models\LlmFunction;
use Illuminate\Database\Seeder;

class GetExistingTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parameters = get_fixture_v2('get_tags_function_parameters.json', false);
        LlmFunction::create([
            'label' => 'get_existing_tags',
            'description' => 'Use this function to get existing tags. Input should be a fully formed MySQL query.',
            'parameters' => $parameters,
        ]);
    }
}
