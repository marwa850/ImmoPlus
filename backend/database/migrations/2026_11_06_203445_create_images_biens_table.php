<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images_biens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bien_id')
                  ->constrained('biens')->cascadeOnDelete();

            $table->string('chemin_image');
            $table->boolean('principale')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images_biens');
    }
};