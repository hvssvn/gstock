<?php

namespace Database\Factories;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Boutique;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    public function definition(): array
    {
        $nomsProduits = [
            'Thiakry', 'Attiéké', 'Savon Koto', 'Tissu Bazin', 'Huile d’arachide',
            'Farine de mil', 'Encens traditionnel', 'Beurre de karité', 'Pagne wax', 'Café Touba'
            /*,
            'Sirop de bissap', 'Miel local', 'Tamarin séché', 'Savon noir', 'Tapis artisanal', 'Lait caillé',
            'Thé sénégalais','Bracelets en perles', 'Huile de neem', 'Batik coloré', 'Sandales en cuir',
            'Sac en raphia', 'Dattes locales', 'Piment en poudre', 'Tissu indigo', 'Farine de manioc',
            'Noix de cajou grillées', 'Sels parfumés', 'Gâteau de mil', 'Boucles d’oreilles artisanales'*/
        ];

        $nom = $this->faker->unique()->randomElement(['Thiakry', 'Attiéké', 'Savon Koto', 'Tissu Bazin', 'Huile d’arachide', 'Farine de mil', 'Encens traditionnel', 'Beurre de karité', 'Pagne wax', 'Café Touba']);
        $code = strtoupper(Str::slug($nom)) . '-' . $this->faker->unique()->numberBetween(100, 999);

        $pa = $this->faker->randomFloat(2, 500, 5000); // prix d'achat
        $pu = $pa + $this->faker->randomFloat(2, 100, 2000); // marge bénéficiaire

        return [
            'nom' => $nom,
            'code' => $code,
            'pa' => $pa,
            'pu' => $pu,
            'qte' => $this->faker->numberBetween(10, 500),
            'photo' => 'produits/' . $this->faker->image('public/storage/produits', 640, 480, null, false),
            'etat' => $this->faker->randomElement(['Activer', 'Désactiver']),
            'categorie_id' => Categorie::factory(),
            'boutique_id' => Boutique::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
