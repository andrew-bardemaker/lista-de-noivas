<?php
header('Content-Type: text/html; charset=utf-8');
include('../inc/inc.verificasession.php');
include('../inc/fpdf/fpdf.php');
include('../inc/inc.configdb.php');
include('../inc/inc.lib.php');

$dataSql='';
 
if (!empty($_GET['id'])) {

    $id = $_GET['id'];
}


if (!empty($_GET['data'])) {

    $data = $_GET['data'];
    $data = dataMY($data);

    $dataSql="and data_fim like '%$data%'";
}

if (!empty($_GET['telefone'])) {

    $telefone = $_GET['telefone'];
}


$desconto = 0;


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
        $this->Cell(0, 15, utf8_decode('Relatório | Cha de Panela '), 0, 0, 'C');
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
        $this->Cell(0, 10, utf8_decode('Data de Emissão: ' . date('d/m/Y H:i:s')), 0, 0, 'L');
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
$pdf->Cell(45, 6, utf8_decode('Data do Evento'), 1, 1, 'L');


if (!empty($telefone)) {


    $sql = "SELECT * FROM dedstudio13_cha where telefone1 = $telefone $dataSql";
    $query = $dba->query($sql);
    $qntd = $dba->rows($query);
    if ($qntd > 0) {
        for ($i = 0; $i < $qntd; $i++) {
            $vet = $dba->fetch($query);
            $id=$vet['id'];
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(70, 6, utf8_decode(stripslashes($vet['noivo'])), 1, 0, 'L');
            $pdf->Cell(70, 6, utf8_decode(stripslashes($vet['noiva'])), 1, 0, 'L');
            $pdf->Cell(70, 6, utf8_decode(stripslashes($vet['loja'])), 1, 0, 'L');
            $pdf->Cell(45, 6, utf8_decode(stripslashes(dataBR($vet['data_fim']))), 1, 1, 'L');
        }
    }else{
        echo 'evento nao encontrado';
        exit;
    }

    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(70, 6, utf8_decode('Produto'), 1, 0, 'L');
    $pdf->Cell(70, 6, utf8_decode('Preço'), 1, 0, 'L');
    $pdf->Cell(70, 6, utf8_decode('Codigo de Barras'), 1, 0, 'L'); 
    $pdf->Cell(20, 6, utf8_decode('Unidades'), 1, 0, 'L');
    $pdf->Cell(45, 6, utf8_decode('Presente'), 1, 1, 'L');














    $sql = "SELECT * FROM dedstudio13_cha_produtos where id_cha=$id";
    $query = $dba->query($sql);
    $qntd = $dba->rows($query);
 

    if ($qntd > 0) {
        for ($i = 0; $i <= $qntd; $i++) {

            $vet = $dba->fetch($query);

            $id_prod = $vet[1];


            if (!empty($id_prod)) {
                $sql2 = "SELECT * FROM dedstudio13_produtos where id=$id_prod";
                $query2 = $dba->query($sql2);
                $qntd2 = $dba->rows($query2);

                if ($qntd2 > 0) {
                    for ($i = 0; $i < $qntd2; $i++) {
                        $vet2 = $dba->fetch($query2);

                        $pdf->SetFont('Arial', '', 7);
                        $pdf->Cell(70, 6, utf8_decode(stripslashes($vet2[2])), 1, 0, 'L');
                        $pdf->Cell(70, 6, utf8_decode("R$" . stripslashes($vet2[3])), 1, 0, 'L');

                        $pdf->Cell(70, 6, utf8_decode(stripslashes($vet2[1])), 1, 0, 'L');

                        $id_item = $vet2[0];
                        $preco = $vet2[3];
                    }
                    
                    $sql3 = "SELECT  quantidade FROM dedstudio13_cha_produtos where id_produto=$id_prod AND id_cha=$id"; 
                    $query3 = $dba->query($sql3);
                    $qntd3 = $dba->rows($query3);
                    if($qntd3 > 0){
                        $vet3 = $dba->fetch($query3);
                        $pdf->Cell(20, 6,$vet3['quantidade'],1,0,'L');
                    }
                } 
                
                if ($vet[3] != 1) {
                    $pdf->Cell(45, 6, utf8_decode('Disponivel'), 1, 1, 'L');
                } else {
                    $pdf->Cell(45, 6, utf8_decode('Indisponivel'), 1, 1, 'L');
                }
            }
        }
    }
} else {

    $sql = "SELECT * FROM dedstudio13_cha where id=$id";
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
            $pdf->Cell(25, 6, utf8_decode(stripslashes($vet['desconto']) . '%'), 1, 1, 'L');
        }
    }

    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(70, 6, utf8_decode('Produto'), 1, 0, 'L');
    $pdf->Cell(70, 6, utf8_decode('Preço'), 1, 0, 'L'); 
    $pdf->Cell(70, 6, utf8_decode('Codigo de Barras'), 1, 0, 'L');
    $pdf->Cell(20, 6, utf8_decode('Unidades'), 1, 0, 'L');
    $pdf->Cell(45, 6, utf8_decode('Presente'), 1, 1, 'L');














    $sql = "SELECT * FROM dedstudio13_cha_produtos where id_cha=$id";
    $query = $dba->query($sql);
    $qntd = $dba->rows($query);


    if ($qntd > 0) {
        for ($i = 0; $i <= $qntd; $i++) {

            $vet = $dba->fetch($query);

            $id_prod = $vet[1];


            if (!empty($id_prod)) {
                $sql2 = "SELECT * FROM dedstudio13_produtos where id=$id_prod";
                $query2 = $dba->query($sql2);
                $qntd2 = $dba->rows($query2);

                if ($qntd2 > 0) {
                    for ($i = 0; $i < $qntd2; $i++) {
                        $vet2 = $dba->fetch($query2);

                        $pdf->SetFont('Arial', '', 7);
                        $pdf->Cell(70, 6, utf8_decode(stripslashes($vet2[2])), 1, 0, 'L');
                        $pdf->Cell(70, 6, utf8_decode("R$" . stripslashes($vet2[3])), 1, 0, 'L');

                        $pdf->Cell(70, 6, utf8_decode(stripslashes($vet2[1])), 1, 0, 'L');

                        $id_item = $vet2[0];
                        $preco = $vet2[3];
                    }
                    
                    $sql3 = "SELECT  quantidade FROM dedstudio13_cha_produtos where id_produto=$id_prod AND id_cha=$id"; 
                    $query3 = $dba->query($sql3);
                    $qntd3 = $dba->rows($query3);
                    if($qntd3 > 0){
                        $vet3 = $dba->fetch($query3);
                        $pdf->Cell(20, 6,$vet3['quantidade'],1,0,'L');
                    }
                }

                if ($vet[3] != 1) {
                    $pdf->Cell(45, 6, utf8_decode('Disponivel'), 1, 1, 'L');
                } else {
                    $pdf->Cell(45, 6, utf8_decode('Indisponivel'), 1, 1, 'L');
                }
            }
        }
    }
}




$pdf->Output(utf8_decode("Cha-de-Panela.pdf"), "D");
