<?php
$host = "mysql.omega";
$username = "cukraszda123";
$password = "gamfcuk";
$database = "cukraszda123";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$database;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Adatbázis kapcsolat hiba: " . $e->getMessage());
}
?>
