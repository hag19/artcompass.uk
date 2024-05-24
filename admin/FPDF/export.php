<?php
require ('fpdf.php');
include('../includes/bdd.php');
$q = 'SELECT * FROM users';
$req = $pdo->prepare($q);
$req->execute();
$result = $req->fetchAll(PDO::FETCH_ASSOC);

//toujours setfont avant cell!

class PDF extends FPDF
{
    function Header()
    {   
        // Police Arial gras 15
        $this->SetFont('Arial', 'B', 15);
        // Décalage
        $this->Cell(70);
        // Titre encadré
        $this->Cell(30, 10, 'Users informations','C');
        // Saut de ligne
        $this->Ln(20);
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
foreach($result as $row){
    $pdf->Cell(40, 10, 'Id:' . $row['id']);
    $pdf->Ln(10);
    $pdf->Cell(40, 10, 'Username:' . $row['username']);
    $pdf->Ln(5);
    $pdf->Cell(40, 10, 'Last name: ' . $row['lname']);
    $pdf->Ln(5);
    $pdf->Cell(40, 10, 'First name: ' . $row['fname']);
    $pdf->Ln(5);
    $pdf->Cell(40, 10, 'email: ' . $row['email']);
    $pdf->Ln(5);
    $pdf->Cell(40, 10, 'role: ' . $row['role']);
    $pdf->Ln(20);
}
$pdf->Output();