<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->unique();
            $table->decimal('pa', 10, 2); // Prix d'achat
            $table->decimal('pu', 10, 2); // Prix de vente
            $table->unsignedInteger('qte');
            $table->string('photo')->nullable();
            $table->enum('etat', ['Activer', 'Désactiver'])->default('Activer');
            $table->foreignId('categorie_id')->constrained('categories');
            $table->foreignId('boutique_id')->constrained('boutiques');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
