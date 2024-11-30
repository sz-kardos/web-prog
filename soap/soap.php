<?php
$client = new SoapClient("http://www.mnb.hu/arfolyamok.asmx?WSDL");

try {
    // Adatok lekérdezése adott devizapárra és dátumra
    $params = [
        'startDate' => '2023-11-01',
        'endDate' => '2023-11-01',
        'currencyNames' => 'EUR,HUF'
    ];
    $response = $client->__soapCall('GetExchangeRates', [$params]);
    $xml = simplexml_load_string($response->GetExchangeRatesResult);
    $rates = $xml->Day->Rate;
    foreach ($rates as $rate) {
        echo $rate['curr'] . ': ' . $rate . '<br>';
    }
} catch (Exception $e) {
    echo 'Hiba: ' . $e->getMessage();
}
?>
