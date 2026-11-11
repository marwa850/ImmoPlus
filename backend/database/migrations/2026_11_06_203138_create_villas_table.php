<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villas', function (Blueprint $table) {
            $table->foreignId('bien_id')
                  ->primary()
                  ->constrained('biens')
                  ->cascadeOnDelete();

            $table->integer('chambres')->default(0);
            $table->integer('salles_de_bain')->default(0);
            $table->integer('nombre_etages')->default(1);
            $table->boolean('piscine')->default(false);
            $table->boolean('jardin')->default(false);
            $table->boolean('garage')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villas');
    }
};