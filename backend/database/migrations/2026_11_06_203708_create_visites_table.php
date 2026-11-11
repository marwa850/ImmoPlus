<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('utilisateur_id')
                  ->constrained('utilisateurs')->restrictOnDelete();

            $table->foreignId('annonce_id')
                  ->constrained('annonces')->cascadeOnDelete();

            $table->dateTime('date_visite');

            $table->enum('statut', [
                'en_attente', 'confirmee', 'refusee',
                'annulee', 'effectuee'
            ])->default('en_attente');

            $table->text('commentaire')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('date_visite', 'idx_visite_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visites');
    }
};