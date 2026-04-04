<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

// AJOUTE CES IMPORTS
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

try {
    echo "=== TEST GESTCOMMDB ===\n\n";
    
    // Initialiser Laravel
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Test de connexion - UTILISE DB MAINTENANT AU LIEU DE \DB
    DB::select('SELECT 1');
    echo "✅ Connexion DB: OK\n";
    
    // Nom de la base - UTILISE Config MAINTENANT
    $dbName = Config::get('database.connections.mysql.database');
    echo "📊 Database: $dbName\n\n";
    
    // Voir les tables
    $tables = DB::select('SHOW TABLES');
    echo "📦 Tables (" . count($tables) . "):\n";
    
    $tableKey = 'Tables_in_' . $dbName;
    
    foreach ($tables as $table) {
        $tableName = $table->$tableKey;
        try {
            $count = DB::table($tableName)->count();
            echo "   - $tableName: $count enregistrements\n";
        } catch (Exception $e) {
            echo "   - $tableName: table vide ou erreur\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DU TEST ===\n";