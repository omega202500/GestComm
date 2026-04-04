<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VenteSeeder extends Seeder
{
    public function run()
    {
        // Supposons que nous avons des clients avec ID 1, 2, 3, 4
        // Supposons que nous avons des produits avec ID 4, 5, 6
        
        DB::table('ventes')->insert([
            // Ventes pour le commercial Gildas (ID 2)
            [
                'commercial_id' => 2, // Gildas
                'produit_id' => 4,
                'client_id' => 1,
                'total_quantite' => 20,
                'prix_unitaire' => 4420.00,
                'montant_total' => 88400.00, // 20 * 4420
                'facture_ref' => 'FAC-2024-001',
                'date_vente' => '2024-01-15 10:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'commercial_id' => 2,
                'produit_id' => 5,
                'client_id' => 2,
                'total_quantite' => 15,
                'prix_unitaire' => 2464.00,
                'montant_total' => 36960.00, // 15 * 2464
                'facture_ref' => 'FAC-2024-002',
                'date_vente' => '2024-01-15 14:45:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'commercial_id' => 2,
                'produit_id' => 6,
                'client_id' => 1,
                'total_quantite' => 30,
                'prix_unitaire' => 1620.00,
                'montant_total' => 48600.00, // 30 * 1620
                'facture_ref' => 'FAC-2024-003',
                'date_vente' => '2024-01-15 16:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Ventes pour le commercial Naelle (ID 3)
            [
                'commercial_id' => 3, // Naelle
                'produit_id' => 4,
                'client_id' => 3,
                'total_quantite' => 35,
                'prix_unitaire' => 4420.00,
                'montant_total' => 154700.00, // 35 * 4420
                'facture_ref' => 'FAC-2024-004',
                'date_vente' => '2024-01-12 09:15:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'commercial_id' => 3,
                'produit_id' => 5,
                'client_id' => 4,
                'total_quantite' => 25,
                'prix_unitaire' => 2464.00,
                'montant_total' => 61600.00, // 25 * 2464
                'facture_ref' => 'FAC-2024-005',
                'date_vente' => '2024-01-12 11:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'commercial_id' => 3,
                'produit_id' => 6,
                'client_id' => 3,
                'total_quantite' => 40,
                'prix_unitaire' => 1620.00,
                'montant_total' => 64800.00, // 40 * 1620
                'facture_ref' => 'FAC-2024-006',
                'date_vente' => '2024-01-12 15:45:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}