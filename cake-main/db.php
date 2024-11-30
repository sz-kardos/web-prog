<?php
// Adatbázis kapcsolat konfiguráció
$servername = "localhost";
$username = "root";
$password = "";
$database = "cukraszda123";

// Kapcsolat létrehozása
$conn = new mysqli($servername, $username, $password, $database);

// Hiba ellenőrzése
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
