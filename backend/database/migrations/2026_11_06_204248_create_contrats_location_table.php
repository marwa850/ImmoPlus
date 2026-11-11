<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats_location', function (Blueprint $table) {
            $table->id();

            $table->foreignId('annonce_id')
                  ->constrained('annonces')->restrictOnDelete();

            $table->foreignId('locataire_id')
                  ->constrained('utilisateurs')->restrictOnDelete();

            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('montant_mensuel', 12, 2);
            $table->decimal('caution', 12, 2)->default(0);

            $table->enum('statut', [
                'en_attente', 'actif', 'termine', 'resilie'
            ])->default('en_attente');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats_location');
    }
};