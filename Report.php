<?php 
require_once('config.php');

	$pdf = new PDF();
	$pdf->SetTitle("Statement of account",false);
	$pdf->AliasNbPages();
	$pdf->AddPage('P','Letter',0); 
	$pdf->SetFont('Times','B',19);
	$pdf->Cell(195,7,'Cloudlink Systems Inc.',0,1,'C');
	$pdf->SetFont('Times','B',13);
	$pdf->Cell(195,7,'Account Details',0,1,'C');
	/*  PETC COD AND CURRENT BALANCE */
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(195,7,'',0,1);
	$pdf->Cell(25,5,'PETC CODE: ',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(95,5,' ',0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(33,5,'Current Balance: ',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(40,5,'00.00',0,1,'R');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(120,5,' ',0,0); // petc name
	$pdf->SetFont('Arial','B',10);
	$pdf->CellFitScale(33,5,'Maintaining balance: ',0,0); //MAINTAINING BALANCE
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(40,5,'(.00)',0,1,'R');
	$pdf->Line(131,42,202,42);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(25,7,'Date from: ',0,0); 
	$pdf->SetFont('Arial','',10);
	$pdf->CellFitScale(25,7,' ',0,0); // DATE FROM 
	$pdf->SetFont('Arial','B',10);
	$pdf->CellFitScale(10,7,'To: ',0,0,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->CellFitScale(60,7,'  ',0,0); //DATE TO
	$pdf->SetFont('Arial','B',10);
	$pdf->CellFitScale(33,7,'Available balance: ',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(40,7,'.00',0,1,'R');
	$pdf->Cell(195,2,'',0,1);
	$pdf->SetX(11);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(30,5,'Date',1,0,'C');
	$pdf->Cell(30,5,'Description',1,0,'C');
	$pdf->Cell(42,5,'Details',1,0,'C');
	$pdf->Cell(30,5,'Debit',1,0,'C');
	$pdf->Cell(30,5,'Credit',1,0,'C');
	$pdf->CellFitScale(30,5,'Balance',1,1,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->SetX(11);
	$pdf->Cell(30,5," ",1,0); 
	$pdf->CellFitScale(30,5," ",1,0); // Description
	$pdf->CellFitScale(42,5," ",1,0); // Details
	$pdf->CellFitScale(30,5,'.00',1,0,'R'); // Debit
	$pdf->CellFitScale(30,5,'.00',1,0,'R'); // credit*/
	$pdf->CellFitScale(30,5,'.00',1,0,'R');// Balance*/
	$pdf->Ln();
	$pdf->Output(0,'no-record.pdf');
?>