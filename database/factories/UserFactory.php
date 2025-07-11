<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use App\Models\Boutique;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $prenoms = ['Awa', 'Moussa', 'Fatou', 'Ibrahima', 'Seynabou', 'Cheikh', 'Mariama', 'Ousmane'];
        $noms = ['Diop', 'Ndiaye', 'Ba', 'Sarr', 'Fall', 'Faye', 'Seck', 'Diallo'];

        return [
            'nom' => $this->faker->randomElement(['Diop', 'Ndiaye', 'Ba', 'Sarr', 'Fall', 'Faye', 'Seck', 'Diallo']),
            'prenom' => $this->faker->randomElement(['Awa', 'Moussa', 'Fatou', 'Ibrahima', 'Seynabou', 'Cheikh', 'Mariama', 'Ousmane']),
            'adresse' => $this->faker->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // mot de passe par défaut
            'date_naissance' => $this->faker->date('Y-m-d', '-18 years'),
            'telephone' => '77' . $this->faker->unique()->numerify('#######'),
            'photo' => 'default.jpg',
            'cni' => $this->faker->unique()->numerify('1#########'),
            'etat' => $this->faker->randomElement(['Activer', 'Désactiver']),
            'boutique_id' => Boutique::factory(), // assure que des boutiques existent
            'role_id' => Role::factory(),         // assure que des rôles existent
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
