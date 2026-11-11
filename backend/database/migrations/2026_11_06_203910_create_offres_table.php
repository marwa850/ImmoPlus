<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();

            $table->foreignId('utilisateur_id')
                  ->constrained('utilisateurs')->restrictOnDelete();

            $table->foreignId('annonce_id')
                  ->constrained('annonces')->cascadeOnDelete();

            $table->decimal('montant', 12, 2);
            $table->text('message')->nullable();
            $table->dateTime('date_offre')->useCurrent();

            $table->enum('statut', [
                'en_attente', 'acceptee', 'refusee', 'annulee'
            ])->default('en_attente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};