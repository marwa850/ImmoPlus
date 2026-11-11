<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contrat_id')
                  ->constrained('contrats_location')->restrictOnDelete();

            $table->decimal('montant', 12, 2);
            $table->date('date_echeance');
            $table->date('date_paiement')->nullable();

            $table->enum('mode_paiement', [
                'especes', 'virement', 'carte', 'autre'
            ])->default('autre');

            $table->enum('statut', [
                'en_attente', 'paye', 'en_retard'
            ])->default('en_attente');

            $table->string('reference', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('date_echeance', 'idx_paiements_echeance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};