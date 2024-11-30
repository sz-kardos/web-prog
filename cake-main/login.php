<?php
session_start();
include 'db.php'; // Az adatbázis kapcsolatot tartalmazó fájl

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Ellenőrzés: mezők kitöltve
    if (!empty($username) && !empty($password)) {
        // Felhasználó keresése az adatbázisban
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Jelszó ellenőrzése
            if (password_verify($password, $user['password'])) {
                // Sikeres belépés
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                echo "<p style='color: green;'>Sikeres belépés! Átirányítás a főoldalra...</p>";
                header("Refresh: 2; url=index.php");
                exit();
            } else {
                echo "<p style='color: red;'>Hibás jelszó!</p>";
            }
        } else {
            echo "<p style='color: red;'>Nincs ilyen felhasználó!</p>";
        }
    } else {
        echo "<p style='color: red;'>Minden mezőt ki kell tölteni!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belépés</title>
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
            background-color: #0056b3;
        }
        p {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Belépés</h1>
        <form method="POST" action="">
            <label for="username">Felhasználónév:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Belépés</button>
        </form>
        <p>Még nincs fiókod? <a href="register.php">Regisztráció</a></p>
    </div>
</body>
</html>
