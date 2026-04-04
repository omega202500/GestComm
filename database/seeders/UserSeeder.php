<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'nom' => 'Admin System',
                'email' => 'admin@gestcomm.com',
                'phone' => '+237612345678',
                'mot_de_passe' => Hash::make('password123'),
                'role' => 'admin',
                'zone_id' => null,
                'statut' => 1,
            ],
            [
                'nom' => 'Gildas',
                'email' => 'gildas@gmail.com',
                'phone' => '+237678563412',
                'mot_de_passe' => Hash::make('password123'),
                'role' => 'terrain',
                'zone_id' => 1,
                'statut' => 1,
            ],
            [
                'nom' => 'Naelle', 
                'email' => 'nalle@gmail.com',
                'phone' => '+237652683801',
                'mot_de_passe' => Hash::make('password123'),
                'role' => 'terrain',
                'zone_id' => 2,
                'statut' => 1,
            ],
            [
                'nom' => 'Kevine',
                'email' => 'kevine@gmail.com',
                'phone' => '+237612345679',
                'mot_de_passe' => Hash::make('password123'),
                'role' => 'chauffeur',
                'zone_id' => 1,
                'statut' => 1,
            ],
            [
                'nom' => 'Cedric',
                'email' => 'cedric@gmail.com', 
                'phone' => '+2376809101112',
                'mot_de_passe' => Hash::make('password123'),
                'role' => 'chauffeur',
                'zone_id' => 2,
                'statut' => 1,
            ]
        ]);
    }
}