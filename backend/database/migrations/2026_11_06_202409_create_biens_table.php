<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proprietaire_id')
                  ->nullable()
                  ->constrained('utilisateurs')
                  ->nullOnDelete();

            $table->enum('type_bien', [
                'appartement',
                'villa',
                'maison'
            ]);

            $table->string('adresse');
            $table->string('ville', 80);
            $table->decimal('superficie', 10, 2);
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index('ville', 'idx_biens_ville');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};