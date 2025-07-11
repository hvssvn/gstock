<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();

            $table->string('numero')->unique(); // Pour éviter les doublons de numéro de vente
            $table->integer('qte')->unsigned(); // Quantité vendue, toujours positive
            $table->date('date');               // Champ correctement typé pour une date
            $table->enum('etat', ['Activer', 'Désactiver'])->default('Activer');
            $table->foreignId('boutique_id')->constrained('boutiques');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
