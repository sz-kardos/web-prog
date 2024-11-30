<?php
$options = [
    'location' => 'http://localhost/soap_server.php',
    'uri' => 'http://localhost/soap_server.php'
];
$client = new SoapClient(null, $options);

try {
    $cookies = $client->getCookies();
    echo "<h2>Sütik listája</h2>";
    echo "<ul>";
    foreach ($cookies as $cookie) {
        echo "<li>" . htmlspecialchars($cookie['name']) . " - " . htmlspecialchars($cookie['price']) . " Ft</li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    echo "Hiba történt: " . $e->getMessage();
}
?>
