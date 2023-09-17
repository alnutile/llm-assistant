<?php

namespace Database\Seeders;

use App\Models\LlmFunction;
use Illuminate\Database\Seeder;

class AddFunctionTagArticle extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parameters = get_fixture_v2('tag_article_function_parameters.json', false);
        LlmFunction::create([
            'label' => 'add_tags_to_article',
            'description' => 'Use this function to add tags to an article.',
            'parameters' => $parameters,
        ]);
    }
}
