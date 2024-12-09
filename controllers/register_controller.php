<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $response['message'] = "Minden mezőt ki kell tölteni!";
        echo json_encode($response);
        exit();
    }

    try {
        // Ellenőrzés: felhasználónév már létezik
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $response['message'] = "A felhasználónév már foglalt!";
            echo json_encode($response);
            exit();
        }

        // Jelszó hasítása és felhasználó mentése
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$username, $hashedPassword]);

        // Munkamenet beállítása
        $userId = $pdo->lastInsertId(); // Az új felhasználó ID-je
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;

        // Átirányítás a főoldalra
        $response['success'] = true;
        $response['message'] = "Sikeres regisztráció!";
        $response['redirect'] = '../index.php'; // A főoldal útvonala
        echo json_encode($response);
        exit();
    } catch (PDOException $e) {
        $response['message'] = "Hiba történt a regisztráció során: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }
}
?>