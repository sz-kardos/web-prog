<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            $response['success'] = true;
            $response['message'] = "Sikeres bejelentkezés!";
            $response['redirect'] = '../index.php';
        } else {
            $response['message'] = "Helytelen felhasználónév vagy jelszó.";
        }
    } catch (PDOException $e) {
        $response['message'] = "Hiba történt: " . $e->getMessage();
    }
}

echo json_encode($response);
exit();
?>
