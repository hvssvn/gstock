<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HistoriqueFactory extends Factory
{
    protected $model = \App\Models\Historique::class;

    public function definition()
    {
        return [
            'model_name' => $this->faker->randomElement(['Produit', 'Vente', 'User', 'Boutique']), 
            'model_id' => $this->faker->randomNumber(), 
            'user_id' => \App\Models\User::factory(), 
            'action' => $this->faker->randomElement(['create', 'update', 'delete']),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'valeur_avant' => $this->faker->optional()->randomElement([
                json_encode(['field1' => 'old value', 'field2' => 123]),
                json_encode(['name' => 'ancienne valeur']),
                null,
            ]),
            'valeur_apres' => $this->faker->optional()->randomElement([
                json_encode(['field1' => 'new value', 'field2' => 456]),
                json_encode(['name' => 'nouvelle valeur']),
                null,
            ]),
        ];
    }
}
