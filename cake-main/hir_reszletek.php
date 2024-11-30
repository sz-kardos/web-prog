<?php
session_start();
include 'db.php';

if (!isset($_GET['postid'])) {
    echo "Hír azonosító hiányzik!";
    exit();
}

$postid = $_GET['postid'];
$stmt = $conn->prepare("SELECT title, image, body, created_at FROM posts WHERE postid = ?");
$stmt->bind_param("s", $postid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "A hír nem található!";
    exit();
}

$hir = $result->fetch_assoc();

// Vélemény hozzáadása
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Bejelentkezés szükséges a véleményezéshez!');</script>";
    } else {
        $user_id = $_SESSION['user_id'];
        $comment = trim($_POST['comment']);

        if (!empty($comment)) {
            $stmt = $conn->prepare("INSERT INTO velemenyek (postid, user_id, comment) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $postid, $user_id, $comment);
            if ($stmt->execute()) {
                echo "<script>alert('Vélemény sikeresen hozzáadva!'); window.location.reload();</script>";
            } else {
                echo "<script>alert('Hiba történt a vélemény hozzáadása során!');</script>";
            }
        } else {
            echo "<script>alert('A vélemény mező nem lehet üres!');</script>";
        }
    }
}

// Vélemények lekérdezése
$comments = $conn->prepare("SELECT velemenyek.id, velemenyek.comment, velemenyek.created_at, felhasznalok.username AS username
                            FROM velemenyek
                            JOIN felhasznalok ON velemenyek.user_id = felhasznalok.id
                            WHERE velemenyek.postid = ?
                            ORDER BY velemenyek.created_at DESC");

$comments->bind_param("s", $postid);
$comments->execute();
$comments_result = $comments->get_result();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($hir['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            color: #ff6600;
            text-align: center;
            margin-bottom: 20px;
        }

        img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .content {
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .comments {
            margin-top: 30px;
        }

        .comments h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .comment {
            background: #f4f4f4;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .comment-form {
            position: relative;
            z-index: 10;
        }


        .comment strong {
            color: #ff6600;
        }

        .comment-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: none;
        }

        .comment-form button {
            background-color: #ff6600;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        .comment-form button:hover {
            background-color: #e65c00;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($hir['title']); ?></h1>
        <?php if (!empty($hir['image'])): ?>
            <img src="<?php echo htmlspecialchars($hir['image']); ?>" alt="<?php echo htmlspecialchars($hir['title']); ?>">
        <?php endif; ?>
        <div class="content">
            <p><?php echo nl2br(htmlspecialchars($hir['body'])); ?></p>
            <p><small>Publikálva: <?php echo htmlspecialchars($hir['created_at']); ?></small></p>
        </div>

        <!-- Vélemények -->
        <div class="comments">
            <h2>Vélemények</h2>
            <?php if ($comments_result->num_rows > 0): ?>
                <?php while ($row = $comments_result->fetch_assoc()): ?>
                    <div class="comment">
                        <strong><?php echo htmlspecialchars($row['username']); ?></strong>
                        <small>(<?php echo htmlspecialchars($row['created_at']); ?>)</small>
                        <p><?php echo htmlspecialchars($row['comment']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nincsenek vélemények.</p>
            <?php endif; ?>
        </div>

        <!-- Vélemény hozzáadása -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" class="comment-form">
                <textarea name="comment" rows="4" placeholder="Írd ide a véleményed..." required></textarea>
                <button type="submit">Vélemény hozzáadása</button>
            </form>
        <?php else: ?>
            <p>Kérlek <a href="login.php">jelentkezz be</a>, hogy véleményt írhass.</p>
        <?php endif; ?>
    </div>
</html>
