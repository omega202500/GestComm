<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ActivitySeeder extends Seeder
{
    /**
     * Exécuter le seeder.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        
        // Créer 10 activités fictives
        for ($i = 0; $i < 10; $i++) {
            Activity::create([
                'user_id' => $faker->numberBetween(1, 5), // suppose qu'il y a au moins 5 utilisateurs
                'type' => $faker->randomElement(['commande', 'vente', 'rapport', 'retour']),
                'reference' => $faker->unique()->numberBetween(1000, 9999),
                'status' => $faker->randomElement(['en_attente', 'valide']),
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at' => now()
            ]);
        }
    }
}