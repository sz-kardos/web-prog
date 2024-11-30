<?php
session_start();
include 'db.php';

// Hírek lekérdezése az adatbázisból
$sql = "SELECT postid, title, image, body, created_at FROM posts WHERE published = 1 ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hírek</title>
    <style>
        /* Alapvető stílusok */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Fejléc */
        header {
            background-color: #ff6600;
            padding: 20px 0;
            text-align: center;
            color: white;
        }

        header h1 {
            font-size: 2.5rem;
            margin: 0;
        }

        /* Blog Grid Layout */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        /* Blog Card */
        .blog-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .blog-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .blog-card-content {
            padding: 20px;
        }

        .blog-card-content h2 {
            font-size: 1.5rem;
            color: #ff6600;
            margin: 0 0 10px 0;
        }

        .blog-card-content p {
            line-height: 1.6;
            margin-bottom: 20px;
            color: #555;
        }

        .blog-card-content a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .blog-card-content a:hover {
            text-decoration: underline;
        }

        /* Lábléc */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Blog Hírek</h1>
    </header>
    <div class="container">
        <div class="blog-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="blog-card">
                        <?php if (!empty($row['image'])): ?>
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <?php else: ?>
                            <img src="default-image.jpg" alt="Alapértelmezett kép">
                        <?php endif; ?>
                        <div class="blog-card-content">
                            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p><?php echo htmlspecialchars(substr($row['body'], 0, 100)) . '...'; ?></p>
                            <a href="hir_reszletek.php?postid=<?php echo urlencode($row['postid']); ?>">Tovább olvasom</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nincsenek hírek.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Blog Hírek - Minden jog fenntartva</p>
    </footer>
</body>
</html>
