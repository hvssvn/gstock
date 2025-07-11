<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategorieFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => $this->faker->unique()->randomElement(['Vêtements traditionnels', 'Chaussures', 'Accessoires', 'Cosmétiques', 'Électronique', 'Alimentation', 'Tissus Wax', 'Bijoux artisanaux', 'Décoration intérieure', 'Parfumerie', 'Lingerie',]),
            'etat' => $this->faker->randomElement(['Activer', 'Désactiver']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
