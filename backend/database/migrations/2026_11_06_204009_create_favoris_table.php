<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favoris', function (Blueprint $table) {
            $table->id();

            $table->foreignId('utilisateur_id')
                  ->constrained('utilisateurs')->cascadeOnDelete();

            $table->foreignId('annonce_id')
                  ->constrained('annonces')->cascadeOnDelete();

            $table->dateTime('date_ajout')->useCurrent();

            $table->unique(
                ['utilisateur_id', 'annonce_id'],
                'uk_favoris_utilisateur_annonce'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favoris');
    }
};