<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProduitSeeder extends Seeder
{
    public function run()
    {
        // D'abord insérer les produits
        $produits = [
            [
                'nom' => 'Lav+ tout usage 5L',
                'description' => 'Détergent liquide pour surfaces - Bidon 5L',
                'prix_unitaire' => 12500,
                'stock' => 150,
                //'categorie_id' => 1, // Ménage
            ],
            [
                'nom' => 'Savon win 350 g',
                'description' => 'Savon win Extra pur',
                'prix_unitaire' => 8500,
                'stock' => 20,
                //'categorie_id' => 1, // Ménage
            ],
            [
                'nom' => 'Javel 1L',
                'description' => 'Eau de javel concentrée - 1L',
                'prix_unitaire' => 11500,
                'stock' => 2,
               // 'categorie_id' => 1, // Ménage
            ],
            [
                'nom' => 'Savon cordelette',
                'description' => 'Savon de toilette hydratant pour toute la famille',
                'prix_unitaire' => 5200,
                'stock' => 120,
                //'categorie_id' => 2, // Liquide
            ],
            [
                'nom' => 'Sauce Tomate 1kg',
                'description' => 'Sauce tomate concentrée - Pot 1kg',
                'prix_unitaire' => 2800,
                'stock' => 160,
                //'categorie_id' => 2, // Liquide
            ],
            [
                'nom' => 'Lait Concentré 400g',
                'description' => 'Lait concentré sucré - Boîte 400g',
                'prix_unitaire' => 1800,
                'stock' => 220,
                //'categorie_id' => 2, // Liquide
            ],
            [
                'nom' => 'Shampoing Revitalisant',
                'description' => 'Shampoing soin capillaire - 400ml',
                'prix_unitaire' => 3800,
                'stock' => 90,
                //'categorie_id' => 3, // Cosmetic
            ],
            [
                'nom' => 'Gel Douche Hydratant',
                'description' => 'Gel douche parfumé - 500ml',
                'prix_unitaire' => 2900,
                'stock' => 130,
                //'categorie_id' => 3, // Cosmetic
            ],
            [
                'nom' => 'Crème Corps Nourrissante',
                'description' => 'Crème hydratante pour le corps - 250ml',
                'prix_unitaire' => 4200,
                'stock' => 80,
                //'categorie_id' => 3, // Cosmetic
            ],
            [
                'nom' => 'Déodorant Spray',
                'description' => 'Déodorant protection 48h - 150ml',
                'prix_unitaire' => 2500,
                'stock' => 110,
               // 'categorie_id' => 3, // Cosmetic
            ]
        ];

        // DB::table('produits')->insert($produits);

        // Ensuite, insérer les remises dans remise_categorie
        // $remises = [
        //     // Produits Ménage (categorie_id = 1)
        //     ['produit_id' => 1, 'type_client' => 'menage', 'taux_remise' => 10],
        //     ['produit_id' => 1, 'type_client' => 'liquide', 'taux_remise' => 8],
        //     ['produit_id' => 1, 'type_client' => 'cosmetic', 'taux_remise' => 5],
            
        //     ['produit_id' => 2, 'type_client' => 'menage', 'taux_remise' => 12],
        //     ['produit_id' => 2, 'type_client' => 'liquide', 'taux_remise' => 10],
        //     ['produit_id' => 2, 'type_client' => 'cosmetic', 'taux_remise' => 6],
            
        //     ['produit_id' => 3, 'type_client' => 'menage', 'taux_remise' => 8],
        //     ['produit_id' => 3, 'type_client' => 'liquide', 'taux_remise' => 7],
        //     ['produit_id' => 3, 'type_client' => 'cosmetic', 'taux_remise' => 4],

        //     ['produit_id' => 4, 'type_client' => 'menage', 'taux_remise' => 5],
        //     ['produit_id' => 4, 'type_client' => 'liquide', 'taux_remise' => 15],
        //     ['produit_id' => 4, 'type_client' => 'cosmetic', 'taux_remise' => 3],
            
        //     ['produit_id' => 5, 'type_client' => 'menage', 'taux_remise' => 4],
        //     ['produit_id' => 5, 'type_client' => 'liquide', 'taux_remise' => 12],
        //     ['produit_id' => 5, 'type_client' => 'cosmetic', 'taux_remise' => 2],
            
        //     ['produit_id' => 6, 'type_client' => 'menage', 'taux_remise' => 3],
        //     ['produit_id' => 6, 'type_client' => 'liquide', 'taux_remise' => 10],
        //     ['produit_id' => 6, 'type_client' => 'cosmetic', 'taux_remise' => 2],

        //     ['produit_id' => 7, 'type_client' => 'menage', 'taux_remise' => 6],
        //     ['produit_id' => 7, 'type_client' => 'liquide', 'taux_remise' => 5],
        //     ['produit_id' => 7, 'type_client' => 'cosmetic', 'taux_remise' => 20],
            
        //     ['produit_id' => 8, 'type_client' => 'menage', 'taux_remise' => 5],
        //     ['produit_id' => 8, 'type_client' => 'liquide', 'taux_remise' => 4],
        //     ['produit_id' => 8, 'type_client' => 'cosmetic', 'taux_remise' => 18],
            
        //     ['produit_id' => 9, 'type_client' => 'menage', 'taux_remise' => 7],
        //     ['produit_id' => 9, 'type_client' => 'liquide', 'taux_remise' => 6],
        //     ['produit_id' => 9, 'type_client' => 'cosmetic', 'taux_remise' => 22],
            
        //     ['produit_id' => 10, 'type_client' => 'menage', 'taux_remise' => 4],
        //     ['produit_id' => 10, 'type_client' => 'liquide', 'taux_remise' => 3],
        //     ['produit_id' => 10, 'type_client' => 'cosmetic', 'taux_remise' => 15],
        // ];

        // DB::table('remise_categorie')->insert($remises);
    }
}