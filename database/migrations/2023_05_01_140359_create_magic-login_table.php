<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('login_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(config('magic-login.user_table'));
            $table->string('token')->unique();
            $table->timestamp('consumed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
        });
    }
};
