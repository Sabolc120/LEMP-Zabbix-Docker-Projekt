<?php
// public_html/index.php
// Ez a fájl ellenőrzi a teljes LEMP láncot (PHP + MariaDB)

// ----------------------------------------------------
// 1. Hostname és környezet kiírása
// ----------------------------------------------------
$hostname = gethostname();
echo "<h2>🚀 LEMP Stack Demó</h2>";
echo "<p>Kérést kiszolgáló konténer (Hostname): <strong>" . htmlspecialchars($hostname) . "</strong></p>";

// ----------------------------------------------------
// 2. MariaDB Kapcsolat Tesztelése
// ----------------------------------------------------

// A csatlakozási adatok (a .env és a docker-compose.yml alapján)
$host = getenv('MARIADB_HOST');
$db   = getenv('MARIADB_DATABASE');
$user = getenv('MARIADB_USER');
$pass = getenv('MARIADB_PASSWORD');
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "✅ **Adatbázis kapcsolat sikeres!** (MariaDB/MySQL) <br>";

     // Adatok lekérdezése
     $stmt = $pdo->query('SELECT id, name, status, last_check FROM hosts');
     $hosts = $stmt->fetchAll();

     echo "<h3>Adatok a Hosts táblából:</h3>";
     echo "<ul>";
     foreach ($hosts as $host) {
         echo "<li>ID: {$host['id']} | Név: **{$host['name']}** | Státusz: {$host['status']} | Utolsó ellenőrzés: {$host['last_check']}</li>";
     }
     echo "</ul>";

} catch (\PDOException $e) {
     echo "❌ **Adatbázis kapcsolódási hiba!** <br>";
     echo "Hibaüzenet: " . $e->getMessage();
}

// ----------------------------------------------------
// 3. Egyéb Információk
// ----------------------------------------------------
echo "<p>PHP Verzió: " . phpversion() . "</p>";

// Ezt a függvényt (phpinfo) soha ne hagyd éles környezetben!
// echo phpinfo(); 

?>