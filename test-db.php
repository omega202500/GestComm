<?php
try {
    $pdo = new PDO('pgsql:host=dpg-d79om6chg0os73emclag-a.oregon-postgres.render.com;port=5432;dbname=gest_comm_phkx', 'gest_comm_phkx_user', 'QQZ0NBsD13CQA695q9fvNuWt4R0oDbVW');
    echo "Connexion réussie !";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
