<?php

namespace Database\Factories;

use App\Models\Boutique;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'motif' => $this->faker->randomElement([
                'Achat tissu', 'Paiement électricité', 'Transport', 'Entretien machine', 'Publicité Facebook',
                'Fournitures bureau', 'Salaire couturier', 'Achats mercerie'
            ]),
            'montant' => $this->faker->randomFloat(2, 1000, 50000),
            'mois' => $this->faker->randomElement([
                'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ]),
            'annee' => $this->faker->year(),
            'etat' => $this->faker->randomElement(['Activer', 'Désactiver']),
            'boutique_id' => Boutique::factory(), // génère une boutique liée
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
