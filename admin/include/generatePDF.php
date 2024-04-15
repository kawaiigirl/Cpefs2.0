<?php
const FPDF_FONTPATH = 'font/';
require('fpdf.php');
global $data;
if(!isset($data) && LOCALHOST)
{
	$data['check_in_date'] = '2017-05-8';
	$data['booking_date'] = '2017-2-1 00:51:20';
	$data['nights'] = 3;
	$data['rate'] = '700.00';
	$data['unit_name'] = 'Peninsular';
	$data['unit_id'] = 5;
	$data['member_name'] = 'Test Member';
	$data['member_address'] = '123 Test St';

	$data['member_suburb'] = 'Brisbane';
	$data['member_postcode'] = '4000';
	$data['invoice_number'] = '5030709';
}

$base_deposit = 100;

// The max width point of the pdf is 210
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',20);
$pdf->Text((210 - $pdf->GetStringWidth('Castlemaine Perkins Employees Friendly Society Ltd'))/2,19,'Castlemaine Perkins Employees Friendly Society Ltd');
$pdf->SetFont('Arial','',12);
$pdf->Text((210 - $pdf->GetStringWidth('A.B.N. 087 649 376'))/2,25,'A.B.N. 087 649 376');

$pdf->SetFont('Arial','',10);
$pdf->Text(10,27,' ');
$pdf->Text(10,31,'GPO Box 44');
$pdf->Text(10,35,'Brisbane Qld 4001');

$pdf->Text(200 - $pdf->GetStringWidth('Telephone: 07 3361 7640'),27,' ');
$pdf->Text(200 - $pdf->GetStringWidth('Mobile: 0411 259 931'),31,'Mobile: 0411 259 931');
$pdf->Text(200 - $pdf->GetStringWidth('0411 259 931'),35,'0421 001 826');
$pdf->Text(200 - $pdf->GetStringWidth('E-mail: admin@cpefs.com.au'),39,'E-mail: admin@cpefs.com.au');
/*
$pdf->Text(200 - $pdf->GetStringWidth('Telephone: 07 3361 7640'),27,' ');
$pdf->Text(200 - $pdf->GetStringWidth('Facsimile: 07 3361 7638'),31,'Facsimile: 07 3361 7638');
$pdf->Text(200 - $pdf->GetStringWidth('Mobile: 0418 780 570'),35,'Mobile: 0418 780 570');
$pdf->Text(200 - $pdf->GetStringWidth('E-mail: admin@cpefs.com.au'),39,'E-mail: admin@cpefs.com.au');
*/



$pdf->SetFont('Arial','',20);
$pdf->Text((210 - $pdf->GetStringWidth('Invoice'))/2,45,'Invoice');

$pdf->SetFont('Arial','B',14);
$pdf->Text(10,54,$data['member_name']);
$pdf->Text(10,59,$data['member_address']);
$add=$data['member_suburb']." ".$data['member_postcode'];
$pdf->Text(10,64,$add);

$invoice_number = $data['invoice_number'];

$date=date("l, jS M Y");
$pdf->SetFont('Arial','B',10);

$pdf->Text(10,72,'Invoice Date: '.$date);
$invoice = 'Invoice No: ' . $invoice_number;
$pdf->SetTextColor(255,0,0);
$pdf->SetFont('Arial','B',15);
$pdf->Text(200 - $pdf->GetStringWidth($invoice),72,$invoice);
$pdf->SetFont('Arial','i',8);
$warning = "Invoice number must be quoted when making a";
$warning2 = "direct deposit or booking will not be confirmed";
$pdf->Text(200-$pdf->GetStringWidth($warning),77,$warning);
$pdf->Text(200-$pdf->GetStringWidth($warning2),80,$warning2);
$pdf->SetTextColor(0,0,0);
// Main section
$pdf->SetFont('Arial','BU',12);
$pdf->Text(10,86,'Description');
$pdf->Text(200 - $pdf->GetStringWidth('Amount'),86,'Amount');		

$desc = '';
$pdf->SetFont('Arial','',10);
$cidaydate=date("l,",strtotime($data['check_in_date']))." ".date("jS M Y",strtotime($data['check_in_date']));
$desc = $data['unit_name'] . ' for ' . $data['nights'] . ' nights';
$in = 'IN: ' . $cidaydate;
$edate=getEndDate($data['check_in_date'],$data['nights']);
$codaydate=date("l,",strtotime($edate))." ".date("jS M Y",strtotime($edate));
$out = 'OUT: ' . $codaydate;
$pdf->SetFont('Arial','B',10);
$pdf->Text(10,92,$desc);
$pdf->SetFont('Arial','',10);
$pdf->Text(10,98,$in);
$pdf->Text(10,102,$out);

$date = $data['check_in_date'];
$date1 = explode(' ',$date);
$date2 = explode('-', $date1[0]);

$adate = $data['booking_date'];
$adate1 = explode(' ',$adate);
$adate2 = explode('-', $adate1[0]);

$deposit_due = mktime(0, 0, 0, $adate2[1]  , $adate2[2]+10, $adate2[0]);
$balance_due = mktime(0, 0, 0, $date2[1]  , $date2[2]-35, $date2[0]);
$total_due = mktime(0, 0, 0, $adate2[1]  , $adate2[2], $adate2[0]);

$rate = $data['rate'];
$nights = $data['nights'];
$amount_due = $rate;
if (($deposit_due > $balance_due) or ($date > $balance_due) or ($nights<7)) {
	$pdf->SetFont('Arial','B',12);
	$amount = 'Total Amount due : ' . date('l, jS M Y', $total_due);
	$pdf->Text(10,109,$amount);
	$pdf->Text(200 - $pdf->GetStringWidth("$".number_format($amount_due, 2, '.', '')),109,"$".number_format($amount_due, 2, '.', ''));
}
else {
	$pdf->SetFont('Arial','',10);
	$deposit = 'Deposit due : ' . date('l, jS M Y', $deposit_due);
	$balance = 'Balance due : ' . date('l, jS M Y', $balance_due);
	$pdf->Text(10,109,$deposit);
	$pdf->Text(10,113,$balance);

	$deposit_amount = ceil($nights/7 ) * $base_deposit;
	if ($deposit_amount<100)
		$deposit_amount = 100;
	$balance_amount = $rate - $deposit_amount;
	$amount = 'Total Amount : ';

	$pdf->Text(200 - $pdf->GetStringWidth("$".number_format($deposit_amount, 2, '.', '')),109,"$".number_format($deposit_amount, 2, '.', ''));
	$pdf->Text(200 - $pdf->GetStringWidth("$".number_format($balance_amount, 2, '.', '')),113,"$".number_format($balance_amount, 2, '.', ''));
	$pdf->SetFont('Arial','B',12);
	$pdf->Text(10,127,$amount);
	$pdf->Text(200 - $pdf->GetStringWidth("$".number_format($amount_due, 2, '.', '')),127,"$".number_format($amount_due, 2, '.', ''));
}


$pdf->SetFont('Arial','B',8);
$pdf->Text(10 ,137,'Banking details:');
$pdf->SetFont('Arial','',8);
$pdf->Text(10 ,141,'Bank: Commonwealth Bank Australia');
$pdf->Text(10,145,'Account Name: Castlemaine Perkins Employees Friendly Society Ltd');
$pdf->Text(10,149,'BSB: 064 123');
$pdf->Text(10,153,'Account number: 10012729');
$pdf->SetFont('Arial','I',8);

$pdf->Output('F','pdf/invoice.pdf');





