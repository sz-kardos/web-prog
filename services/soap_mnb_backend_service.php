<?php require_once 'soap_mnb_service.php';

header("Content-Type: application/json");

$action = $_GET['action'] ?? null;

if ($action === 'getCurrencyPairs') {
    $pairs = [
        "EUR/HUF",
        "USD/HUF",
        "GBP/HUF",
        "CHF/HUF",
        "AUD/HUF",
        "RUB/HUF"
    ];
    echo json_encode(['pairs' => $pairs]);
    exit;
}

if ($action === 'getRate') {
    $currency = $_POST['currency'] ?? null;
    $date = $_POST['date'] ?? null;

    if (!$currency || !$date) {
        echo json_encode(['error' => 'Hiányzó adatok: devizapár vagy dátum.']);
        exit;
    }

    $currencyName = explode("/", $currency)[0];

    $rate = getExchangeRate($date, $currencyName);
    echo json_encode($rate);
    exit;
}
if ($action === 'getMonthlyRates') {
    $currency = $_POST['currency'] ?? null;
    $year = $_POST['year'] ?? null;
    $month = $_POST['month'] ?? null;

    if (!$currency || !$year || !$month) {
        echo json_encode(['error' => 'Hiányzó adatok: devizapár, év vagy hónap.']);
        exit;
    }

    $rates = getMonthlyRates($year, $month, $currency);
    echo json_encode($rates);
    exit;
}


echo json_encode(['error' => 'Érvénytelen művelet.']);
