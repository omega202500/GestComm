<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== MARQUAGE DES MIGRATIONS COMME EXÉCUTÉES ===\n";

$migrations = [
    '2019_12_14_000001_create_personal_access_tokens_table',
    '2025_11_01_000001_create_zones_table',
    '2025_11_01_000002_create_categories_table',
    '2025_11_01_000003_create_produits_table',
    '2025_11_01_000004_create_users_table',
    '2025_11_01_000005_create_commandes_table',
    '2025_11_01_000006_create_commande_items_table',
    '2025_11_01_000007_create_ventes_table',
    '2025_11_01_000008_create_vente_items_table',
    '2025_11_01_000009_create_retours_table',
    '2025_11_01_000010_create_chargements_table',
    '2025_11_01_000011_create_versements_table',
    '2025_11_01_000012_create_remise',
    '2025_11_28_081743_update_remise_categorie_table',
    '2025_11_28_134856_add_produit_id_to_remise_categorie_table'
];

$batch = 1;
foreach ($migrations as $migration) {
    if (!DB::table('migrations')->where('migration', $migration)->exists()) {
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => $batch
        ]);
        echo "✅ '$migration' marquée comme exécutée (batch $batch)\n";
    } else {
        echo "ℹ️  '$migration' déjà existante\n";
    }
    $batch++;
}

echo "=== TERMINÉ ===\n";