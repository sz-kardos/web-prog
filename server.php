<?php
session_start();
header("Content-Type: application/json");
include 'db.php';

// Csak bejelentkezett felhasználók férhetnek hozzá
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Bejelentkezés szükséges"]);
    exit();
}

// REST metódusok kezelése
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

switch ($method) {
    case 'GET':
        if ($id) {
            // Egy adott üzenet lekérdezése
            $stmt = $conn->prepare("SELECT * FROM comments WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
        } else {
            // Összes üzenet lekérdezése
            $stmt = $conn->prepare("SELECT comments.id, comments.message, comments.created_at, users.username, hirek.title 
                                    FROM comments 
                                    JOIN users ON comments.user_id = users.id 
                                    JOIN hirek ON comments.news_id = hirek.id");
            $stmt->execute();
            $result = $stmt->get_result();
            $comments = [];
            while ($row = $result->fetch_assoc()) {
                $comments[] = $row;
            }
            echo json_encode($comments);
        }
        break;

    case 'POST':
        // Új üzenet létrehozása
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['news_id'], $data['message'])) {
            http_response_code(400);
            echo json_encode(["error" => "Hír azonosító és üzenet szükséges"]);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO comments (user_id, news_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $_SESSION['user_id'], $data['news_id'], $data['message']);
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Üzenet létrehozva"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Hiba történt"]);
        }
        break;

    case 'PUT':
        // Üzenet módosítása
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "Üzenet ID szükséges"]);
            exit();
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['message'])) {
            http_response_code(400);
            echo json_encode(["error" => "Új üzenet szöveg szükséges"]);
            exit();
        }

        // Ellenőrizzük, hogy az üzenet a felhasználóé
        $stmt = $conn->prepare("SELECT user_id FROM comments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comment = $result->fetch_assoc();

        if ($comment['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(["error" => "Nincs jogosultság módosítani"]);
            exit();
        }

        $stmt = $conn->prepare("UPDATE comments SET message = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $data['message'], $id, $_SESSION['user_id']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Üzenet módosítva"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Hiba történt"]);
        }
        break;

    case 'DELETE':
        // Üzenet törlése
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "Üzenet ID szükséges"]);
            exit();
        }

        // Ellenőrizzük, hogy az üzenet a felhasználóé
        $stmt = $conn->prepare("SELECT user_id FROM comments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comment = $result->fetch_assoc();

        if ($comment['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(["error" => "Nincs jogosultság törölni"]);
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $_SESSION['user_id']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Üzenet törölve"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Hiba történt"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Metódus nem támogatott"]);
        break;
}
?>
