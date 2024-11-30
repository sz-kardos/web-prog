<?php
// Adatbázis kapcsolat
$host = '127.0.0.1';
$dbname = 'cukraszda';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

// Kérés feldolgozása
$request_method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Funkciók
function getCookies() {
    global $conn;
    $result = $conn->query("SELECT * FROM cookies");
    $cookies = [];
    while ($row = $result->fetch_assoc()) {
        $cookies[] = $row;
    }
    echo json_encode($cookies);
}

function getCookie($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM cookies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cookie = $result->fetch_assoc();
    echo json_encode($cookie);
}

function createCookie($data) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO cookies (name, price) VALUES (?, ?)");
    $stmt->bind_param("sd", $data['name'], $data['price']);
    $stmt->execute();
    echo json_encode(["message" => "Cookie létrehozva!"]);
}

function updateCookie($id, $data) {
    global $conn;
    $stmt = $conn->prepare("UPDATE cookies SET name = ?, price = ? WHERE id = ?");
    $stmt->bind_param("sdi", $data['name'], $data['price'], $id);
    $stmt->execute();
    echo json_encode(["message" => "Cookie frissítve!"]);
}

function deleteCookie($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM cookies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(["message" => "Cookie törölve!"]);
}

// Kérés irányítása
switch ($request_method) {
    case 'GET':
        if (!empty($_GET['id'])) {
            getCookie($_GET['id']);
        } else {
            getCookies();
        }
        break;

    case 'POST':
        createCookie($input);
        break;

    case 'PUT':
        if (!empty($_GET['id'])) {
            updateCookie($_GET['id'], $input);
        }
        break;

    case 'DELETE':
        if (!empty($_GET['id'])) {
            deleteCookie($_GET['id']);
        }
        break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>
