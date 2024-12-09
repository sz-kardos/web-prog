<?php
require_once 'menu.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Bootstrap HTML alap
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deviza Váltó</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<?php include_once 'header.php'; ?>
<body class="bg-light">
<div class="container py-5">
    <h1 class="text-center mb-4">Deviza Váltó</h1>

    <form method="post" id="form1" class="mb-4">
        <div class="mb-3">
            <label for="date" class="form-label">Dátum:</label>
            <input type="date" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <div class="mb-3">
            <label for="mennyi" class="form-label">Összeg:</label>
            <input type="number" class="form-control" name="mennyi" id="mennyi" placeholder="Összeg" value="1" required>
        </div>
        <div class="mb-3">
            <label for="deviza" class="form-label">Átváltandó deviza:</label>
            <select class="form-select" name="deviza" id="deviza">
                <option value="USD">USD - Amerikai dollár</option>
                <option value="EUR">EUR - Euro</option>
                <option value="HUF">HUF - Magyar forint</option>
                <option value="GBP">GBP - Angol font</option>
                <option value="AUD">AUD - Ausztrál dollár</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="deviza2" class="form-label">Cél deviza:</label>
            <select class="form-select" name="deviza2" id="deviza2">
                <option value="HUF">HUF - Magyar forint</option>
                <option value="USD">USD - Amerikai dollár</option>
                <option value="EUR">EUR - Euro</option>
                <option value="GBP">GBP - Angol font</option>
                <option value="AUD">AUD - Ausztrál dollár</option>
            </select>
        </div>
        <button type="submit" name="valtas" class="btn btn-primary">Átváltás</button>
    </form>

    <?php
    if (isset($_POST['valtas'])) {
        // Ide jön az átváltási logika
        // Feltételezve, hogy az átváltás már működik...
        $_POST['eredmeny'] = 123.45; // Példaérték
    }

    if (isset($_POST['eredmeny'])): ?>
        <div class="alert alert-success">
            <strong>Eredmény:</strong> <?php echo number_format($_POST['eredmeny'], 2); ?> 
            <?php echo htmlspecialchars($_POST['deviza2']); ?>
        </div>
    <?php endif; ?>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Árfolyamok diagramja</h5>
            <canvas id="myChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('myChart');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['2023-01-01', '2023-01-02', '2023-01-03'], // Példa adatok
            datasets: [{
                label: 'USD',
                data: [1.1, 1.2, 1.3],
                borderColor: 'rgb(255, 99, 132)',
                borderWidth: 2
            }, {
                label: 'EUR',
                data: [1.2, 1.3, 1.4],
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include_once 'footer.php'; ?>
</html>
