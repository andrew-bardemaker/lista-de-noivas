<?php
header('Content-Type: text/html; charset=utf-8');
include('../inc/inc.verificasession.php');
include('../inc/fpdf/fpdf.php');
include('../inc/inc.configdb.php');
include('../inc/inc.lib.php');



class PDF extends FPDF
{
    //Page header
    function Header()
    {
        //Logo
        $this->Image('../images/logo.jpg', 10, 8, 33);
        //Arial bold 15
        $this->SetFont('Arial', 'B', 10);
        //Title
        $this->Cell(0, 15, utf8_decode('Relatório | Chas de Panela'), 0, 0, 'C');
        //Line break
        $this->Ln(20);
    }

    //Page footer
    function Footer()
    {
        //Position at 1.5 cm from bottom
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial', '', 8);
        //Page number
        $this->Cell(0, 10, utf8_decode('Data de Emissão: ' . date('d/m/Y H:i:s') ), 0, 0, 'L');
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }
}





//Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L', 'A4');

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(70, 6, utf8_decode('Anfitrião(a)'), 1, 0, 'L');
$pdf->Cell(70, 6, utf8_decode('Anfitriã(o)'), 1, 0, 'L');
$pdf->Cell(70, 6, utf8_decode('Unidade'), 1, 0, 'L');
$pdf->Cell(20, 6, utf8_decode('Data do Evento'), 1, 0, 'L');
$pdf->Cell(25, 6, utf8_decode('bonificação'), 1, 1, 'L');

$sql = "SELECT * FROM dedstudio13_cha";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
    for ($i = 0; $i < $qntd; $i++) {
        $vet = $dba->fetch($query);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(70, 6, utf8_decode(stripslashes($vet['noivo'])), 1, 0, 'L');
        $pdf->Cell(70, 6, utf8_decode(stripslashes($vet['noiva'])), 1, 0, 'L');
        $pdf->Cell(70, 6, utf8_decode(stripslashes($vet['loja'])), 1, 0, 'L');
        $pdf->Cell(20, 6, utf8_decode(stripslashes(dataBR($vet['data_fim']))), 1, 0, 'L');
        $pdf->Cell(25, 6, utf8_decode(stripslashes($vet['desconto']).'%'), 1, 1, 'L');
    }
}









$pdf->Output(utf8_decode("Relatório-Cha-de-Panela.pdf"), "D");
