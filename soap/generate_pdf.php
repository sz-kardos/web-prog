<?php
require_once('tcpdf/tcpdf.php');
include 'db.php';

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

$html = '<h1>Menü Lista</h1>';
$html .= '<table border="1" cellpadding="5">';
$html .= '<tr><th>ID</th><th>Név</th><th>URL</th></tr>';

$result = $conn->query("SELECT * FROM menu");
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td>' . $row['id'] . '</td>';
    $html .= '<td>' . $row['nev'] . '</td>';
    $html .= '<td>' . $row['url'] . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';

$pdf->writeHTML($html);
$pdf->Output('menu.pdf', 'I');
?>
