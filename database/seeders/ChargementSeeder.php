<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChargementSeeder extends Seeder
{
    public function run()
    {
        DB::table('chargements')->insert([
            [
                'chauffeur_id' => 4,
                'produit_id' => 1,
                'quantite' => 50,
                'date_chargement' => '2024-01-14',
                'statut' => 'livre',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chauffeur_id' => 4,
                'produit_id' => 4,
                'quantite' => 60,
                'date_chargement' => '2024-01-14',
                'statut' => 'livre',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chauffeur_id' => 5,
                'produit_id' => 7,
                'quantite' => 30,
                'date_chargement' => '2024-01-11',
                'statut' => 'livre',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chauffeur_id' => 5,
                'produit_id' => 4,
                'quantite' => 40,
                'date_chargement' => '2024-01-11',
                'statut' => 'livre',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}