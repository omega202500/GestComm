<?php
// Fichier : check_db.php
echo "<h1>Vérification connexion BD</h1>";

// Test 1 : PDO direct
echo "<h3>Test 1 : Connexion PDO directe</h3>";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gest_comm', 'root', '');
    echo "✅ PDO avec 127.0.0.1 : OK<br>";
} catch (PDOException $e) {
    echo "❌ PDO avec 127.0.0.1 : " . $e->getMessage() . "<br>";
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=gest_comm', 'root', '');
    echo "✅ PDO avec localhost : OK<br>";
} catch (PDOException $e) {
    
    echo "❌ PDO avec localhost : " . $e->getMessage() . "<br>";
}

// Test 2 : MySQLi
echo "<h3>Test 2 : Connexion MySQLi</h3>";
$mysqli1 = @new mysqli('127.0.0.1', 'root', '', 'gest_comm');
if ($mysqli1->connect_error) {
    echo "❌ MySQLi avec 127.0.0.1 : " . $mysqli1->connect_error . "<br>";
} else {
    echo "✅ MySQLi avec 127.0.0.1 : OK<br>";
    $mysqli1->close();
}

$mysqli2 = @new mysqli('localhost', 'root', '', 'gest_comm');
if ($mysqli2->connect_error) {
    echo "❌ MySQLi avec localhost : " . $mysqli2->connect_error . "<br>";
} else {
    echo "✅ MySQLi avec localhost : OK<br>";
    $mysqli2->close();
}

// Test 3 : Extensions chargées
echo "<h3>Test 3 : Extensions PHP chargées</h3>";
echo "PDO drivers disponibles : ";
print_r(PDO::getAvailableDrivers());
echo "<br>";

echo "Extension mysqli chargée : " . (extension_loaded('mysqli') ? '✅ OUI' : '❌ NON') . "<br>";
echo "Extension pdo_mysql chargée : " . (extension_loaded('pdo_mysql') ? '✅ OUI' : '❌ NON') . "<br>";

// Test 4 : php.ini utilisé
echo "<h3>Test 4 : Configuration PHP</h3>";
echo "php.ini utilisé : " . php_ini_loaded_file() . "<br>";
echo "Dossier extensions : " . ini_get('extension_dir') . "<br>";
?>