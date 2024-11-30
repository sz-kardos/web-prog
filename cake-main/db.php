<?php
// Adatbázis kapcsolat konfiguráció
$host = "localhost";
$username = "root";
$password = "";
$database = "cukraszda123";
$dbname = 'cukraszda123';

/// MySQLi kapcsolat (ha szükséges)
$conn = new mysqli($host, $username, $password, $dbname);

// Hiba ellenőrzése MySQLi kapcsolatnál
if ($conn->connect_error) {
    die("MySQLi kapcsolat sikertelen: " . $conn->connect_error);
}

// PDO kapcsolat létrehozása
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO kapcsolat sikertelen: " . $e->getMessage());
}
?>
