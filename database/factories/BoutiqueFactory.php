<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BoutiqueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => $this->faker->company(),
            'description' => $this->faker->sentence(10),
            'adresse' => $this->faker->address(), // Peut être remplacée par un quartier connu
            'site_web' => 'https://www.' . $this->faker->domainWord() . '.sn',
            'telephone' => $this->faker->unique()->numerify('77#-###-###'), // Format courant au Sénégal
            'photo' => 'images/boutiques/' . $this->faker->image('public/storage/images/boutiques', 640, 480, null, false),
            'etat' => $this->faker->randomElement(['Activer', 'Désactiver']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
