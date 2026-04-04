<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Désactiver les contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Vider les tables
        DB::table('users')->truncate();
        DB::table('zones')->truncate();
        DB::table('produits')->truncate();
        DB::table('commandes')->truncate();
        DB::table('ventes')->truncate();
        // DB::table('versements')->truncate();
        DB::table('chargements')->truncate();
        DB::table('retours')->truncate();
        // DB::table('remises_categorie')->truncate();
        DB::table('migrations')->truncate();
        // DB::table('personal_access_tokens')->truncate();
        DB::table('clients')->truncate();
        //   DB::table('categories_produits')->truncate();






        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Appeler les seeders
        $this->call([
            ActivitySeeder::class,
            ZoneSeeder::class,
            ClientSeeder::class,
            UserSeeder::class,
            ProduitSeeder::class,
            CommandeSeeder::class,
            VenteSeeder::class,
            // VersementSeeder::class,
            ChargementSeeder::class,
            RetourSeeder::class,
        ]);
    }
}


