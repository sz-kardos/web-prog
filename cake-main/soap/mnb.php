<?php
// SOAP kliens inicializálása
$client = new SoapClient("http://www.mnb.hu/arfolyamok.asmx?wsdl");

// Alapértelmezett értékek
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$currency = isset($_POST['currency']) ? $_POST['currency'] : 'EUR';

$dates = [];
$rates = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($startDate) && !empty($endDate) && !empty($currency)) {
        $params = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currencyNames' => $currency
        ];

        try {
            // Árfolyamok lekérdezése a SOAP szolgáltatáson keresztül
            $response = $client->GetExchangeRates($params);
            $ratesXml = simplexml_load_string($response->GetExchangeRatesResult);

            // Adatok tárolása a táblázathoz és grafikontához
            foreach ($ratesXml->Day as $day) {
                $date = (string)$day['date'];
                foreach ($day->Rate as $rate) {
                    if ((string)$rate['curr'] === $currency) {
                        $dates[] = $date;
                        $rates[] = (float)$rate;
                    }
                }
            }
        } catch (Exception $e) {
            die('Hiba történt: ' . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devizaárfolyamok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Devizaárfolyamok Lekérdezése</h1>

        <!-- Lekérdezési űrlap -->
        <div class="card p-4 shadow-sm">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="start_date" class="form-label">Kezdő dátum:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" required value="<?php echo htmlspecialchars($startDate); ?>">
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">Végdátum:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" required value="<?php echo htmlspecialchars($endDate); ?>">
                </div>
                <div class="mb-3">
                    <label for="currency" class="form-label">Deviza:</label>
                    <select id="currency" name="currency" class="form-select" required>
                        <option value="EUR" <?php echo $currency === 'EUR' ? 'selected' : ''; ?>>EUR</option>
                        <option value="USD" <?php echo $currency === 'USD' ? 'selected' : ''; ?>>USD</option>
                        <option value="GBP" <?php echo $currency === 'GBP' ? 'selected' : ''; ?>>GBP</option>
                        <option value="CHF" <?php echo $currency === 'CHF' ? 'selected' : ''; ?>>CHF</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Lekérdezés</button>
            </form>
        </div>

        <!-- Árfolyamok megjelenítése -->
        <?php if (!empty($dates) && !empty($rates)): ?>
            <div class="mt-5">
                <h2 class="text-center mb-4"><?php echo htmlspecialchars($currency); ?>/HUF Árfolyamok</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Dátum</th>
                                <th><?php echo htmlspecialchars($currency); ?>/HUF Árfolyam</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dates as $index => $date): ?>
                                <tr>
                                    <td><?php echo $date; ?></td>
                                    <td><?php echo $rates[$index]; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <canvas id="exchangeRateChart"></canvas>
                </div>
            </div>

            <!-- Grafikon -->
            <script>
                const ctx = document.getElementById('exchangeRateChart').getContext('2d');
                const exchangeRateChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($dates); ?>,
                        datasets: [{
                            label: '<?php echo htmlspecialchars($currency); ?>/HUF Árfolyam',
                            data: <?php echo json_encode($rates); ?>,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Dátum'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Árfolyam'
                                },
                                beginAtZero: false
                            }
                        }
                    }
                });
            </script>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p class="text-center text-danger mt-4">Nincsenek elérhető adatok a megadott időszakra vagy devizára.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
