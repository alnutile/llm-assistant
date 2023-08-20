<?php

use App\Models\Message;
use App\Models\MetaData;
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
        Schema::create('message_meta_data', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Message::class);
            $table->foreignIdFor(MetaData::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_meta_data');
    }
};
