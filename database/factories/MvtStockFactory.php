<?php

namespace Database\Factories;

use App\Models\MvtStock;
use App\Models\Produit;
use App\Models\Boutique;
use Illuminate\Database\Eloquent\Factories\Factory;

class MvtStockFactory extends Factory
{
    protected $model = MvtStock::class;

    public function definition(): array
    {
        return [
            'qte' => $this->faker->numberBetween(1, 100),
            'type' => $this->faker->randomElement(['Entrer', 'Sortir', 'Correction_pos', 'Correction_neg']),
            'motif' => $this->faker->randomElement(['cmde_client', 'vente', 'autre']),
            'produit_id' => Produit::factory(),     // Factory associée
            'boutique_id' => Boutique::factory(),   // Factory associée
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
