<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories_produits')->insert([
            ['id' => 1, 'nom' => 'Ménage', 'description' => 'Produits d\'entretien ménager'],
            ['id' => 2, 'nom' => 'Liquide', 'description' => 'Produits alimentaires liquides'],
            ['id' => 3, 'nom' => 'Cosmetic', 'description' => 'Produits cosmétiques et hygiène'],
            ['id' => 4, 'nom' => 'Prestige', 'description' => 'Produits de luxe et prestige'],

        ]);
    }
}