<?php

namespace Database\Seeders;

use App\Models\Boutique;
use App\Models\Categorie;
use App\Models\Depense;
use App\Models\Historique;
use App\Models\LigneVente;
use App\Models\MvtStock;
use App\Models\Produit;
use App\Models\ResumerJournalier;
use App\Models\Role;
use App\Models\User;
use App\Models\Vente;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Désactiver temporairement les contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Réinitialiser les tables clés
        Boutique::truncate();
        Categorie::truncate();
        Role::truncate();
        User::truncate();
        Vente::truncate();
        Produit::truncate();
        Depense::truncate();
        Historique::truncate();
        LigneVente::truncate();
        MvtStock::truncate();
        ResumerJournalier::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Boutique::factory(5)->create(); // 5 boutiques
        Categorie::factory(1)->create(); // 3 catégories
        Role::factory(1)->create(); // 2 rôles
        User::factory(2)->create(); // 4 utilisateurs
        Vente::factory(5)->create(); // 30 ventes
        Produit::factory(3)->create(); // 10 produits
        Depense::factory(8)->create(); // 8 dépenses
        Historique::factory(40)->create(); // 40 historiques
        LigneVente::factory(60)->create(); // 60 lignes de ventes
        MvtStock::factory(40)->create(); // 40 mouvements de stock
        ResumerJournalier::factory(10)->create(); // 10 résumés journaliers
    }
}
