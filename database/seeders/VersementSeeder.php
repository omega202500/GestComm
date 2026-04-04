<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VersementSeeder extends Seeder
{
    public function run()
    {
        DB::table('versements')->insert([
            [
                'commande_id' => 1,
                'commercial_terrain_id' => 2,
                'montant' => 100000,
                'date_versement' => '2024-01-15',
                'mode_paiement' => 'mobile_money',
                'reference' => 'MM20240115123456',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'commande_id' => 1,
                'commercial_terrain_id' => 2,
                'montant' => 85000,
                'date_versement' => '2024-01-16',
                'mode_paiement' => 'espece',
                'reference' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'commande_id' => 4,
                'commercial_terrain_id' => 3,
                'montant' => 234500,
                'date_versement' => '2024-01-12',
                'mode_paiement' => 'carte',
                'reference' => 'CARTE20240112987654',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}