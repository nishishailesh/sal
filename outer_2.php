<?php
session_start();
$nojunk='defined';
require_once 'common.php';
//require_once 'common.js';
require_once('tcpdf/tcpdf.php');
$link=connect();




//rpp is raw per page
$rpp=15;
$GLOBALS['total_pages']='';
$GLOBALS['college']='Government Medical College, Majura Gate, Surat';
$GLOBALS['allowances']='Report on Pay and Allowances Bill';
$GLOBALS['deductions']='Report on Pay Bill Deductions';
$GLOBALS['grand']=array();

$array_1=prepare_array_1($link,$_POST['bill_group'],$_POST['bill_number'],$rpp);
$remark=$array_1[0]['remark'];
$array_2=prepare_array_2($array_1,$rpp);
$array_3=prepare_array_3($array_2);
$array_4=prepare_array_4($array_3);

//echo '<pre>';
//print_r($array_4);

/////Set EDP

$EDP0101_P=$array_4['Pay_of_Officer_0101(+)'] + $array_4['Grade_Pay_of_Officer_0101(+)'];




class ACCOUNT extends TCPDF {

	public function Header() 
	{
	}
	
	public function Footer() 
	{
	}	
}

function write_text($pdf,$text, $x,$y, $w,$h)
{
$pdf->SetFont('courier','B',10);
$pdf->SetXY($x,$y);
$pdf->SetTextColor(0);
$pdf->MultiCell($w, $h, $text , $border=0, $align='R', 
					$fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, 
					$ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);	
}

function write_text_fill_left($pdf,$text, $x,$y, $w,$h)
{
$pdf->SetFont('courier','B',10);
$pdf->SetXY($x,$y);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$pdf->SetDrawColor(0);

$pdf->Rect($x, $y,$w,$h,'F');
$pdf->MultiCell($w, $h, $text , $border=1, $align='L', 
					$fill=true, $ln=1, $x='', $y='', $reseth=true, $stretch=0, 
					$ishtml=false, $autopadding=false, $maxh=0, $valign='T', $fitcell=false);	
}

function write_text_big_fill($pdf,$text, $x,$y, $w,$h)
{
$pdf->SetFont('courier','B',15);
$pdf->SetXY($x,$y);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$pdf->SetDrawColor(0);

$pdf->Rect($x, $y,$w,$h,'F');
$pdf->MultiCell($w, $h, $text , $border=1, $align='L', 
					$fill=true, $ln=1, $x='', $y='', $reseth=true, $stretch=0, 
					$ishtml=false, $autopadding=false, $maxh=0, $valign='T', $fitcell=false);
}

$pdf = new ACCOUNT('L', 'mm', 'A3', true, 'UTF-8', false);
$pdf->SetFont('dejavusans', '', 9);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//The A3 size print measures 29.7 x 42.0cm
//29 cm
//06 cm left right margin
//23 cm remain
//16 columns
//1.44 cm per column
//6.25% for each column
$pdf->SetMargins(30, 20, 30);
$pdf->AddPage();

function outer_front($pdf,$array_4,$remark)
{
	$img_file = 'outer_front.jpg';
	$pdf->Image($img_file, 30, 20, 0, 0, '', '', '', false, 300, '', false, false, 0);

	$EDP0101_P=$array_4['Pay_of_Officer_0101(+)'] + $array_4['Grade_Pay_of_Officer_0101(+)'];
	write_text($pdf,$EDP0101_P,164,118,20,5);
	$EDP0102_P=$array_4['Pay_of_Establishment_0102(+)'] + $array_4['Grade_Pay_of_Establishment_0102(+)'];
	write_text($pdf,$EDP0102_P,164,121.5,20,5);
	write_text($pdf,$array_4['Leave_Salary_Encash_0109(+)'],164,125,20,5);
	write_text($pdf,$array_4['Dearness_Allowance_0103(+)'],164,129,20,5);
	write_text($pdf,$array_4['House_Rent_Allowance_0110(+)'],164,134,20,5);
	write_text($pdf,$array_4['Compansatory_Local_Allowance_0111(+)'],164,139,20,5);
	write_text($pdf,$array_4['Interim_Relief_0112(+)'],164,145,20,5);
	write_text($pdf,$array_4['Transport_Allowance_0113(+)'],164,150,20,5);
	$EDP0104_P=$array_4['Special_Post_Allow_0104(+)']+$array_4['Family_Welfare_Allow_0104(+)']
				+$array_4['Ceiling_Extra_0104(+)']+$array_4['BA_0104(+)'];
	write_text($pdf,$EDP0104_P,164,154,20,5);
	write_text($pdf,$array_4['Medical_Allowance_0107(+)'],164,158,20,5);
	write_text($pdf,$array_4['Washing_Allowance_0132(+)'],164,163,20,5);
	write_text($pdf,$array_4['Uniform_Allowance_0131(+)'],164,168,20,5);
	write_text($pdf,$array_4['Nursing_Allownace_0129(+)'],164,173,20,5);

	write_text_fill_left($pdf,'0128 NPPA         0128(+)',112,184.5,55,4);
	write_text($pdf,$array_4['NPA_0128(+)'],164,184.5,20,5);
	write_text($pdf,$array_4['gross'],164,196,20,5);

	write_text($pdf,$array_4['Festival_A_5701(-)'],164,201,20,5);
	write_text($pdf,$array_4['Food_Grains_A_5801(-)'],164,205,20,5);
	$EDP0101_M=$array_4['Pay_of_Officer_0101(-)'] + $array_4['Pay_of_Establishment_0102(-)'];
	write_text($pdf,$EDP0101_M,164,209,20,5);

	$total=$array_4['gross'];
	$gross_total=$array_4['gross']-$array_4['Festival_A_5701(-)']-
									$array_4['Food_Grains_A_5801(-)']-
									$array_4['Pay_of_Officer_0101(-)'] - 
									$array_4['Pay_of_Establishment_0102(-)'];

	write_text($pdf,$gross_total,164,219,20,5);
	write_text($pdf,$array_4['net'],164,249,20,5);
	write_text($pdf,$array_4['net'],164,253,20,5);

	write_text($pdf,$array_4['Income_Tax_9510(-)'],239,47,20,5);
	write_text($pdf,$array_4['GPF_IV_9531(-)'],239,59,20,5);
	write_text($pdf,$array_4['CPF_9690(-)'],239,65,20,5);

	//write_text_fill_left($pdf,'GPF non Cl-IV   9670(-)',188,76,55,4);
	write_text($pdf,$array_4['GPF_non_IV_9670(-)'],307,53,20,5);
	write_text($pdf,$array_4['Rent_of_Building_9560(-)'],239,81,20,5);
	write_text($pdf,$array_4['Professional_Tax_9570(-)'],239,95,20,5);
	write_text($pdf,$array_4['SIS_I_9581(-)'],239,108,20,5);
	write_text($pdf,$array_4['SIS_S_9582(-)'],239,118,20,5);
	write_text($pdf,$array_4['HBA_9591(-)'],239,178,20,5);

	$total_a_deduction=$array_4['Income_Tax_9510(-)']+
							$array_4['GPF_IV_9531(-)']+
							$array_4['CPF_9690(-)']+
							$array_4['Rent_of_Building_9560(-)']+
							$array_4['Professional_Tax_9570(-)']+
							$array_4['SIS_I_9581(-)']+
							$array_4['SIS_S_9582(-)']+
							$array_4['HBA_9591(-)'];

	$total_deduction=$total_a_deduction+$array_4['GPF_non_IV_9670(-)'];
	write_text($pdf,$total_a_deduction,239,201,20,5);
	write_text($pdf,$total_deduction,307,137,20,5);
	$net_total=$gross_total-$total_deduction;
	write_text($pdf,$net_total,307,141.5,20,5);

	//12170501
	if(round(($_POST['bill_group']/1000000),0)==12)
	{
		write_text_big_fill($pdf,'Gazetted',156,39,30,12);
	}
	elseif(round(($_POST['bill_group']/1000000),0)==34)
	{
		write_text_big_fill($pdf,'Non - Gazetted',156,39,30,12);
	}
		
	write_text($pdf,$_POST['bill_group'].'-'.$_POST['bill_number'],162,51,25,12);
		write_text_big_fill($pdf,$remark,79,67,35,10);
}

outer_front($pdf,$array_4,$remark);
					
$pdf->Output('example_006.pdf', 'I');


function mk_sql($bill_group,$bill_number)
{
$sql='
 SELECT *, 
 `Pay_of_Officer_0101(+)`
+ `Grade_Pay_of_Officer_0101(+)`
+ `Pay_of_Establishment_0102(+)`
+ `Grade_Pay_of_Establishment_0102(+)`
+`NPA_0128(+)` 
+`Leave_Salary_Encash_0109(+)`
+`Dearness_Allowance_0103(+)`
+`Compansatory_Local_Allowance_0111(+)`
+`House_Rent_Allowance_0110(+)`
+`Medical_Allowance_0107(+)`+
`BA_0104(+)`+
`Transport_Allowance_0113(+)`+
`Interim_Relief_0112(+)`+
`Washing_Allowance_0132(+)`+
`Uniform_Allowance_0131(+)`+
`Nursing_Allownace_0129(+)`+
`Special_Post_Allow_0104(+)`+
`Family_Welfare_Allow_0104(+)`+
`Ceiling_Extra_0104(+)`

as gross,

`Income_Tax_9510(-)`
+`Rent_of_Building_9560(-)`
+`Professional_Tax_9570(-)`
+`SIS_I_9581(-)`
+`SIS_S_9582(-)`
+`GPF_non_IV_9670(-)`
+`GPF_IV_9531(-)`
+`CPF_9690(-)`
+`Pay_of_Officer_0101(-)`
+`Pay_of_Establishment_0102(-)`
+`Festival_A_5701(-)`
+`Food_Grains_A_5801(-)`
+`Car_A_9741(-)`
+`HBA_9591(-)`

as deduction,

(select gross)- (select deduction) as net

from salary

where bill_group=\''.$bill_group.'\' and bill_number=\''.$bill_number.'\'';
return $sql;

}

function prepare_array_1($link,$bill_group,$bill_number,$rpp)
{
	$sql=mk_sql($bill_group,$bill_number);
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	$tot=mysqli_num_rows($result);
	//echo $tot;
	while($result_array=mysqli_fetch_assoc($result))
		{	
			$array_1[]=$result_array;
		}
	return $array_1;
}

function prepare_array_2($array_1,$page_size)
{
		return array_chunk($array_1,$page_size, true);
}


function prepare_array_3($array_2)
{
	$query_fields=array_keys($array_2[0][0]);
		foreach($array_2 as $page_number=>$page_array)
		{
			foreach($page_array as $salary_number=>$salary_array)
			{
				foreach($query_fields as $field=>$value)
				{
					$array_3[$page_number][$value]=array_sum(array_column($page_array,$value));
				}
			}
		}
		return $array_3;
}

function prepare_array_4($array_3)
{
	$query_fields=array_keys($array_3[0]);
	foreach($array_3 as $page_number=>$page_total)
	{
		foreach($query_fields as $field=>$value)
		{		
			$array_4[$value]=array_sum(array_column($array_3,$value));
		}
	}
	return $array_4;
}


?>

