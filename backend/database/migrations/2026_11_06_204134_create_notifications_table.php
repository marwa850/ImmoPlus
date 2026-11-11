<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('utilisateur_id')
                  ->constrained('utilisateurs')->cascadeOnDelete();

            $table->string('message');
            $table->string('type', 50)->nullable();
            $table->boolean('lu')->default(false);
            $table->dateTime('date_creation')->useCurrent();

            $table->index('utilisateur_id', 'idx_notifications_utilisateur');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};