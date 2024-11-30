<?php
session_start();
include 'db.php';

// Üzenet inicializálása
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Jelszó hash-elése
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Adatok beszúrása a users táblába
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'regisztralt')");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            $message = 'Sikeres regisztráció! Átirányítás a bejelentkezéshez...';
            echo "<script>alert('$message'); window.location.href = 'login.php';</script>";
            exit();
        } else {
            $message = 'Hiba történt a regisztráció során: ' . $conn->error;
            echo "<script>alert('$message');</script>";
        }
    } else {
        $message = 'Minden mezőt ki kell tölteni!';
        echo "<script>alert('$message');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            margin-top: 20px;
            padding: 10px;
            background-color: orange;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: darkorange;
        }
        p {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Regisztráció</h1>
        <form method="POST" action="">
            <label for="username">Felhasználónév:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Regisztráció</button>
        </form>
        <p>Már van fiókod? <a href="login.php">Jelentkezz be!</a></p>
    </div>
</body>
</html>
