<?php

namespace Database\Factories;

use App\Models\Vente;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class LigneVenteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'qte' => $this->faker->numberBetween(1, 10),
            'vente_id' => Vente::factory(),      // crée une vente liée
            'produit_id' => Produit::factory(),  // crée un produit lié
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
