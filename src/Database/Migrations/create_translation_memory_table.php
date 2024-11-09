<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translation_memory', function (Blueprint $table) {
            $table->id();
            $table->string('source_locale', 10);
            $table->string('target_locale', 10);
            $table->text('source_text');
            $table->text('translated_text');
            $table->float('similarity_score')->default(1.0);
            $table->timestamps();

            $table->index(['source_locale', 'target_locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_memory');
    }
};