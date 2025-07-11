<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resumer_journaliers', function (Blueprint $table) {
            $table->id();
            $table->decimal('totalVente', 12, 2)->default(0);
            $table->decimal('totalDepense', 12, 2)->default(0);
            $table->enum('mois', [
                'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ]);
            $table->year('annee');
            $table->enum('etat', ['Activer', 'Désactiver'])->default('Activer');
            $table->foreignId('boutique_id')->constrained('boutiques');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resumer_journaliers');
    }
};
