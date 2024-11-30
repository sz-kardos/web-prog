<?php
// Adatbázis kapcsolat betöltése
include 'db.php';

// Menü betöltése
require_once 'menu.php';


?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kapcsolatfelvétel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .contact-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #34bf49;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #2da740;
        }

        .success-message, .error-message {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <h2>Lépj velünk kapcsolatba</h2>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $message = htmlspecialchars($_POST['message']);

            if (!empty($name) && !empty($email) && !empty($message)) {
                // Mentés egy log fájlba
                $log = "messages.log";
                $log_message = "Név: $name\nEmail: $email\nÜzenet: $message\n---\n";
                if (file_put_contents($log, $log_message, FILE_APPEND)) {
                    echo '<div class="success-message">Köszönjük, az üzenetét megkaptuk!</div>';
                } else {
                    echo '<div class="error-message">Hiba történt az üzenet mentése során.</div>';
                }
            } else {
                echo '<div class="error-message">Minden mezőt ki kell tölteni!</div>';
            }
        }
        ?>
        <form action="kapcsolat.php" method="POST">
            <label for="name">Név</label>
            <input type="text" id="name" name="name" placeholder="Add meg a neved" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Add meg az email címed" required>

            <label for="message">Üzenet</label>
            <textarea id="message" name="message" placeholder="Írd meg az üzeneted" rows="6" required></textarea>

            <button type="submit">Üzenet küldése</button>
        </form>
    </div>
</body>
</html>
