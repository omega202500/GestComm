<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $clients = [
            // Zone Nord (zone_id = 1)
            ['nom' => 'Supermarket ABC', 'telephone' => '+2250102030406', 'zone_id' => 1],
            ['nom' => 'Boutique Familiale', 'telephone' => '+2250506070801', 'zone_id' => 1],
            ['nom' => 'Magasin Général Nord', 'telephone' => '+2251112131415', 'zone_id' => 1],
            ['nom' => 'Épicerie Nord Express', 'telephone' => '+2251617181920', 'zone_id' => 1],
            
            // Zone Sud (zone_id = 2)
            ['nom' => 'Salon de Beauté Élégance', 'telephone' => '+2250708091012', 'zone_id' => 2],
            ['nom' => 'Restaurant Le Délicieux', 'telephone' => '+2250901011121', 'zone_id' => 2],
            ['nom' => 'Pharmacie Santé Sud', 'telephone' => '+2252122232425', 'zone_id' => 2],
            ['nom' => 'Boulangerie Sud', 'telephone' => '+2252627282930', 'zone_id' => 2],
            
            // Zone Est (zone_id = 3)
            ['nom' => 'Superette Est', 'telephone' => '+2253132333435', 'zone_id' => 3],
            ['nom' => 'Quincaillerie Est', 'telephone' => '+2253637383940', 'zone_id' => 3],
            ['nom' => 'Café Est', 'telephone' => '+2254142434445', 'zone_id' => 3],
            
            // Zone Ouest (zone_id = 4)
            ['nom' => 'Poissonnerie Ouest', 'telephone' => '+2254647484950', 'zone_id' => 4],
            ['nom' => 'Boucherie Ouest', 'telephone' => '+2255152535455', 'zone_id' => 4],
            ['nom' => 'Primeur Ouest', 'telephone' => '+2255657585960', 'zone_id' => 4],
        ];
        
        // Préparer les données avec timestamps
        $data = [];
        $now = now();
        
        foreach ($clients as $client) {
            $data[] = array_merge($client, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        
        // Insérer en masse
        DB::table('clients')->insert($data);
        
        $this->command->info(count($data) . ' clients insérés avec succès.');
    }
} 