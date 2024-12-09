<?php
function getExchangeRate($date, $currency) {
    $wsdl = "http://www.mnb.hu/arfolyamok.asmx?wsdl";
    try {
        $client = new SoapClient($wsdl, ['trace' => true, 'exceptions' => true]);

        // Csak az első valutát adjuk meg, mivel a SOAP nem fogad párokat az MNB API-ban.
        $currencyName = explode("/", $currency)[0];

        $response = $client->GetExchangeRates([
            'startDate' => $date,
            'endDate' => $date,
            'currencyNames' => $currencyName
        ]);

        $xml = simplexml_load_string($response->GetExchangeRatesResult);

        // Az XML adat feldolgozása
        $rates = json_decode(json_encode($xml), true);

        if (isset($rates['Day']) && isset($rates['Day']['Rate'])) {
            return [
                'date' => $rates['Day']['@attributes']['date'],
                'rate' => is_array($rates['Day']['Rate']) ? $rates['Day']['Rate'][0] : $rates['Day']['Rate']
            ];
        }

        return ['error' => "Nincs elérhető árfolyam az adott napra: {$date}, devizapár: {$currency}."];
    } catch (Exception $e) {
        return ['error' => "SOAP hiba: " . $e->getMessage()];
    }
}
function getMonthlyRates($year, $month, $currency) {
    $wsdl = "http://www.mnb.hu/arfolyamok.asmx?wsdl";
    try {
        $client = new SoapClient($wsdl, ['trace' => true, 'exceptions' => true]);

        // A hónap első és utolsó napjának meghatározása
        $startDate = "{$year}-{$month}-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        //Az MNB API csak egy devizát fogad, nem devizapárt
        $currencyName = explode("/", $currency)[0];

        $response = $client->GetExchangeRates([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currencyNames' => $currencyName
        ]);

        $xml = simplexml_load_string($response->GetExchangeRatesResult);

        $rates = [];
        if (isset($xml->Day)) {
            foreach ($xml->Day as $day) {
                $date = (string)$day['date'];
                $rate = isset($day->Rate) ? (string)$day->Rate : "N/A";

                //Csak a forintra váltott árfolyamokat tároljuk
                $rates[] = [
                    'date' => $date,
                    'rate' => $rate
                ];
            }
        }

        return $rates;
    } catch (Exception $e) {
        return ['error' => "SOAP hiba: " . $e->getMessage()];
    }
}




