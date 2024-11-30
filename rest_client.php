<?php
$base_url = "http://localhost/rest_server.php";

// GET kérés
function getCookies() {
    global $base_url;
    $response = file_get_contents($base_url);
    $cookies = json_decode($response, true);
    foreach ($cookies as $cookie) {
        echo $cookie['name'] . " - " . $cookie['price'] . " Ft<br>";
    }
}

// POST kérés
function createCookie($name, $price) {
    global $base_url;
    $data = ["name" => $name, "price" => $price];
    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($base_url, false, $context);
    echo $response;
}

getCookies();
createCookie("Vaníliás süti", 300);
?>
