<?php

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
        Schema::create('history_words', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->string('word');
            $table->dateTime('added');

            $table->unique(['user_id', 'word']);

            $table->foreign('user_id')->references('id')
            ->on('users')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_words');
    }
};
