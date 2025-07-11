<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boutiques', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->text('description')->nullable();
            $table->string('adresse')->nullable();
            $table->string('site_web')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('photo')->nullable();
            $table->enum('etat', ['Activer', 'Désactiver'])->default('Activer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boutiques');
    }
};
