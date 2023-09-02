<?php

use App\Models\LlmFunction;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $params = get_fixture_v2('content_to_voice.json', false);
        LlmFunction::firstOrCreate([
            'label' => 'content_to_voice',
        ], [
            'label' => 'content_to_voice',
            'description' => 'Use the string of content to create a voice audio version of the content using an external service',
            'parameters' => $params,
            'active' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
