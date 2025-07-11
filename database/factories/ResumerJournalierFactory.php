<?php

namespace Database\Factories;

use App\Models\ResumerJournalier;
use App\Models\Boutique;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResumerJournalierFactory extends Factory
{
    protected $model = ResumerJournalier::class;

    public function definition(): array
    {
        $mois = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];

        return [
            'totalVente' => $this->faker->randomFloat(2, 10000, 500000),  // FCFA
            'totalDepense' => $this->faker->randomFloat(2, 5000, 300000), // FCFA
            'mois' => $this->faker->randomElement($mois),
            'annee' => $this->faker->year($max = 'now'),
            'etat' => $this->faker->randomElement(['Activer', 'Désactiver']),
            'boutique_id' => Boutique::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
