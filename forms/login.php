<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
    } else {
        $error = 'Érvénytelen felhasználónév vagy jelszó.';
    }
}
?>

<?php include '../templates/header.php'; ?>
<h2>Bejelentkezés</h2>
<form method="post" action="login.php">
    <label>Felhasználónév:</label><br>
    <input type="text" name="username" required><br>
    <label>Jelszó:</label><br>
    <input type="password" name="password" required><br>
    <button type="submit">Bejelentkezés</button>
</form>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
<?php include '../templates/footer.php'; ?>
