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
        $params = get_fixture_v2('get_content_from_url.json', false);
        LlmFunction::firstOrCreate([
            'label' => 'get_content_from_url',
        ], [
            'label' => 'get_content_from_url',
            'description' => 'This allows a user to put a URL into the question and it will then return the content for you to continue on with processing',
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
