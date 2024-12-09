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
            background-color: orange; /* Sárga háttér */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label, input, button {
            margin-top: 10px;
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
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: darkorange;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Regisztráció</h1>
        <form id="registerForm">
            <label for="username">Felhasználónév:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Regisztráció</button>
        </form>
        <p>Van már fiókod? <a href="login.php">Jelentkezz be!</a></p>
    </div>

<script>
function handleSubmit(event) {
    event.preventDefault();

    const formData = new FormData(document.getElementById('registerForm'));

    fetch('controllers/register_controller.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Kapott JSON:', data);
        alert(data.message);
        if (data.success && data.redirect) {
            window.location.href = data.redirect;
        }
    })
    .catch(error => {
        console.error('Hiba történt:', error);
        alert('Hiba történt a regisztráció során.');
    });

    return false; // Az űrlap alapértelmezett küldésének tiltása
}
</script>


</body>
</html>
