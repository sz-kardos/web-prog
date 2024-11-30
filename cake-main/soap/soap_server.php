<?php
// Adatbázis kapcsolat
$host = 'mysql.omega'; 
$db = 'cukraszda';
$user = 'cukraszda';
$pass = 'jelszo';
$charset = 'utf8mb4';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

// SOAP Szolgáltatás osztály
class CookieService {
    public function getCookies() {
        global $conn;
        $sql = "SELECT name, price FROM cookies";
        $result = $conn->query($sql);

        $cookies = [];
        while ($row = $result->fetch_assoc()) {
            $cookies[] = $row;
        }

        return $cookies;
    }
}

// SOAP Szerver inicializálása
$options = [
    'uri' => 'http://localhost/soap_server.php'
];
$server = new SoapServer(null, $options);
$server->setClass('CookieService');
$server->handle();
?>
