<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bien_id')
                  ->constrained('biens')->restrictOnDelete();

            $table->string('titre', 150);
            $table->enum('type_transaction', ['vente', 'location']);
            $table->decimal('prix', 12, 2);

            $table->enum('statut', [
                'brouillon', 'disponible', 'reservee',
                'vendue', 'louee', 'archivee'
            ])->default('brouillon');

            $table->timestamps();

            $table->index('statut', 'idx_annonces_statut');
            $table->index('type_transaction', 'idx_annonces_transaction');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};