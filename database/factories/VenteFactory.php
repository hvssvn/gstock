<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VenteFactory extends Factory
{
    protected $model = \App\Models\Vente::class;

    public function definition()
    {
        return [
            'numero' => strtoupper('VTE-' . $this->faker->unique()->bothify('??###')), 
            // Exemple: VTE-AB123, unique pour éviter doublons
            'qte' => $this->faker->numberBetween(1, 100), // Quantité positive
            'date' => $this->faker->date(), // Date aléatoire valide
            'etat' => $this->faker->randomElement(['Activer', 'Désactiver']),
            'boutique_id' => \App\Models\Boutique::factory(), 
            // Génère une boutique associée si besoin (relation)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
