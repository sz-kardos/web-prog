
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Süti Adatok Megjelenítése</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .table-container {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="mb-4">SOAP Adatok Megjelenítése</h1>

    <?php
    try {
        // SOAP kliens inicializálása SSL ellenőrzés kikapcsolásával
        $options = [
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ])
        ];
        $client = new SoapClient("localhost", $options);

        // Süti adatok lekérése
        $suti = $client->getSuti();
        echo '<div class="table-container">';
        echo '<h2>Süti Adatok</h2>';
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>ID</th><th>Név</th><th>Típus</th><th>Díjazott</th></tr></thead><tbody>';
        foreach ($suti as $item) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($item['id']) . '</td>';
            echo '<td>' . htmlspecialchars($item['nev']) . '</td>';
            echo '<td>' . htmlspecialchars($item['tipus']) . '</td>';
            echo '<td>' . ($item['dijazott'] ? 'Igen' : 'Nem') . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></div>';

        // Ár adatok lekérése
        $ar = $client->getAr();
        echo '<div class="table-container">';
        echo '<h2>Ár Adatok</h2>';
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>ID</th><th>Süti ID</th><th>Érték</th><th>Egység</th></tr></thead><tbody>';
        foreach ($ar as $item) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($item['id']) . '</td>';
            echo '<td>' . htmlspecialchars($item['sutiid']) . '</td>';
            echo '<td>' . htmlspecialchars($item['ertek']) . '</td>';
            echo '<td>' . htmlspecialchars($item['egyseg']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></div>';

        // Tartalom adatok lekérése
        $tartalom = $client->getTartalom();
        echo '<div class="table-container">';
        echo '<h2>Tartalom Adatok</h2>';
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>ID</th><th>Süti ID</th><th>Mentés</th></tr></thead><tbody>';
        foreach ($tartalom as $item) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($item['id']) . '</td>';
            echo '<td>' . htmlspecialchars($item['sutiid']) . '</td>';
            echo '<td>' . htmlspecialchars($item['mentes']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></div>';

    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Hiba történt: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
