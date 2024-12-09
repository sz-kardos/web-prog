<?php
require_once '../config/database.php';
require_once '../vendor/tecnickcom/tcpdf/tcpdf.php';

// Választott adatok lekérdezése
$tipus = $_POST['tipus'];
$mentes = $_POST['mentes'];
$egyseg = $_POST['egyseg'];

$stmt = $pdo->prepare("
    SELECT 
        suti.nev AS suti_nev, 
        suti.tipus AS suti_tipus,
        tartalom.mentes AS tartalom_mentes,
        ar.ertek AS ar_ertek,
        ar.egyseg AS ar_egyseg
    FROM 
        suti
    INNER JOIN 
        tartalom ON suti.id = tartalom.sutiid
    INNER JOIN 
        ar ON suti.id = ar.sutiid
    WHERE 
        suti.tipus = :tipus AND tartalom.mentes = :mentes AND ar.egyseg = :egyseg   
");
$stmt->execute(['tipus' => $tipus, 'mentes' => $mentes, 'egyseg' => $egyseg]);

$result = $stmt->fetch();

if (!$result) {
    die("Nincs találat a kiválasztott adatokra.");
}

// PDF létrehozása TCPDF használatával
$pdf = new TCPDF();
$pdf->AddPage();

// Fejléc
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Süti Információk', 0, 1, 'C');

// Tartalom
$pdf->SetFont('helvetica', '', 12);
$html = '
    <h3>Kiválasztott Süti Részletei:</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>Süti Neve</th>
            <td>' . $result['suti_nev'] . '</td>
        </tr>
        <tr>
            <th>Típus</th>
            <td>' . $result['suti_tipus'] . '</td>
        </tr>
        <tr>
            <th>Alapanyag Mentes</th>
            <td>' . $result['tartalom_mentes'] . '</td>
        </tr>
        <tr>
            <th>Ár</th>
            <td>' . $result['ar_ertek'] .  '</td>
        </tr>
        <tr>
            <th>Egység</th>
            <td>' . $result['ar_egyseg']  . '</td>
        </tr>
    </table>
';
$pdf->writeHTML($html, true, false, true, false, '');

// PDF mentése vagy letöltése
$pdf->Output('suti_informaciok.pdf', 'D');
