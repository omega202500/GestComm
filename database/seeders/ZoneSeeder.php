<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneSeeder extends Seeder
{
    public function run()
    {
        DB::table('zones')->insert([
            [
                'nom' => 'Zone Nord', 
                'description' => 'Zone couvrant les régions du nord',
                'region' => 'Nord',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nom' => 'Zone Sud', 
                'description' => 'Zone couvrant les régions du sud',
                'region' => 'Sud',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nom' => 'Zone Est', 
                'description' => 'Zone couvrant les régions de l\'est',
                'region' => 'Est',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nom' => 'Zone Ouest', 
                'description' => 'Zone couvrant les régions de l\'ouest',
                'region' => 'Ouest',
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);
    }
}