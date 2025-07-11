<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->unsignedBigInteger('model_id');
            $table->foreignId('user_id')->constrained('users');
            $table->string('action');
            $table->timestamp('date')->useCurrent();
            $table->json('valeur_avant')->nullable();
            $table->json('valeur_apres')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historiques');
    }
};
