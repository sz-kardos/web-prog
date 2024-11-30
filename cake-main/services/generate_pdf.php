<?php
require_once __DIR__ . '/../db.php'; // Adatbázis kapcsolat
require_once __DIR__ . '/../../vendor/autoload.php'; // TCPDF betöltése

if (!isset($_POST['tipus'], $_POST['mentes'], $_POST['egyseg'])) {
    die("Hiányzó adatok!");
}

$tipus = $_POST['tipus'];
$mentes = $_POST['mentes'];
$egyseg = $_POST['egyseg'];

try {
    $stmt = $pdo->prepare("
        SELECT s.nev AS suti_nev, t.mentes, a.ar, a.egyseg
        FROM suti AS s
        INNER JOIN tartalom AS t ON s.tipus = t.tipus
        INNER JOIN ar AS a ON s.tipus = a.tipus
        WHERE s.tipus = :tipus AND t.mentes = :mentes AND a.egyseg = :egyseg
    ");
    $stmt->execute([
        'tipus' => $tipus,
        'mentes' => $mentes,
        'egyseg' => $egyseg
    ]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        die("Nincs találat az adott szűrésre.");
    }

    // TCPDF inicializálása
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('PDF Generátor');
    $pdf->SetTitle('Sütemények PDF');
    $pdf->SetHeaderData('', 0, 'Sütemény Információk', 'Generált PDF');
    $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

    $pdf->AddPage();

    // PDF tartalom
    $html = "<h1>Sütemények</h1><table border='1' cellpadding='5'>
                <thead>
                    <tr>
                        <th>Sütemény neve</th>
                        <th>Mentes</th>
                        <th>Ár</th>
                        <th>Egység</th>
                    </tr>
                </thead>
                <tbody>";
    foreach ($rows as $row) {
        $html .= "<tr>
                    <td>{$row['suti_nev']}</td>
                    <td>{$row['mentes']}</td>
                    <td>{$row['ar']} Ft</td>
                    <td>{$row['egyseg']}</td>
                  </tr>";
    }
    $html .= "</tbody></table>";

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('sutemenyek.pdf', 'D');

} catch (Exception $e) {
    die("Hiba történt: " . $e->getMessage());
}
