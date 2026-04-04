<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommandeSeeder extends Seeder
{
    public function run()
    {
        DB::table('commandes')->insert([
            [
                'id' => 1,
                'commercial_id' => 2,
                'zone_id' => 1,
                'client_id' => 1,
                // 'client_nom' => 'Supermarket ABC',
                'client_tel' => '+2250102030406',
                'statut' => 'livree',
                'date_livraison' => '2024-01-15',
                'montant_total' => 185000,
                'notes' => 'Client fidèle'
            ],
            [
                'id' => 2,
                'client_id' => 2,
                'commercial_id' => 2,
                'zone_id' => 1,
                // 'client_nom' => 'Boutique Familiale',
                'client_tel' => '+2250506070801',
                'statut' => 'validee',
                'date_livraison' => '2024-01-18', 
                'montant_total' => 89200,
                'notes' => 'Nouveau client'
            ],
            [
                'id' => 3,
                'client_id' => 3,
                'commercial_id' => 3, 
                'zone_id' => 2,
                // 'client_nom' => 'Salon de Beauté Élégance',
                'client_tel' => '+2250708091012',
                'statut' => 'en_attente',
                'date_livraison' => '2024-01-20',
                'montant_total' => 65400,
                'notes' => 'Livraison avant 10h'
            ],
            [
                'id' => 4,
                'client_id' => 4,
                'commercial_id' => 3,
                'zone_id' => 2, 
                'client_tel' => '+2250901011121',
                'statut' => 'livree',
                'date_livraison' => '2024-01-12',
                'montant_total' => 234500,
                'notes' => 'Commande urgente'
            ]
        ]);
    }
}