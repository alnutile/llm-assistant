<?php

use App\Models\LlmFunction;
use App\Models\Message;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('llm_functions', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->longText('description')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        Schema::create('llm_function_message', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Message::class);
            $table->foreignIdFor(LlmFunction::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llm_functions');
    }
};
