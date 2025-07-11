<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mvt_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('qte');
            $table->enum('type', [
                'Entrer', 'Sortir', 'Correction_pos', 'Correction_neg'
            ]);
            $table->enum('motif', ['cmde_client', 'vente', 'autre']);
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('boutique_id')->constrained('boutiques')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mvt_stocks');
    }
};