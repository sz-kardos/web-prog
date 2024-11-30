<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Bejelentkezés szükséges a törléshez!');</script>";
        exit();
    }

    $comment_id = intval($_POST['comment_id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM velemenyek WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $comment_id, $user_id);
    if ($stmt->execute()) {
        echo "<script>alert('Komment sikeresen törölve!'); window.location.href = 'hir_reszletek.php?postid=" . $_GET['postid'] . "';</script>";
    } else {
        echo "<script>alert('Hiba történt a komment törlése során!');</script>";
    }
}
?>
