<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $noms = ['Administrateur', 'Gérant', 'Caissier', 'Stockiste', 'Comptable', 'Vendeur',];

        return [
            'nom' => $this->faker->unique()->randomElement(['Administrateur', 'Gérant', 'Caissier', 'Stockiste', 'Comptable', 'Vendeur',]),
            'description' => $this->faker->sentence(8),
            'etat' => $this->faker->randomElement(['Activer', 'Désactiver']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
