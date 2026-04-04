<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RetourSeeder extends Seeder
{
    public function run()
    {
        DB::table('retours')->insert([
            [
                'commande_id' => 1,
                'produit_id' => 6,
                'quantite' => 1,
                'raison' => 'Emballage endommagé pendant le transport',
                'date_retour' => '2024-01-16',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'commande_id' => 4,
                'produit_id' => 5,
                'quantite' => 1,
                'raison' => 'Produit périmé - Retour accepté',
                'date_retour' => '2024-01-13',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}