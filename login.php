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
            top: 15px;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 10px;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 50%;
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
<?php include 'header.php'; ?>
<body>
    
    <div class="container">
        <h1>Belépés</h1>
        <form id="loginForm">
            <label for="username">Felhasználónév:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Belépés</button>
        </form>
        <p>Még nincs fiókod? <a href="register.php">Regisztráció</a></p>
    </div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault(); // Az alapértelmezett űrlapküldést tiltja

            const formData = new FormData(this);

            fetch('controllers/login_controller.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message); // Üzenet megjelenítése
                if (data.success && data.redirect) {
                    window.location.href = data.redirect; // Átirányítás a főoldalra
                }
            })
            .catch(error => {
                console.error('Hiba történt:', error);
                alert('Hiba történt a bejelentkezés során.');
            });
        });
    </script>
</body>
</html>
