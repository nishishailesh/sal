<?php
session_start();
$nojunk='defined';
require_once 'common.php';
//require_once 'common.js';
require_once('tcpdf/tcpdf.php');
require_once('Numbers/Words.php');
$link=connect();




//rpp is raw per page
//echo '<pre>';

$rpp=20;
$GLOBALS['total_pages']='';
$GLOBALS['college']='Government Medical College, Majura Gate, Surat';
$GLOBALS['allowances']='Report on Pay and Allowances Bill';
$GLOBALS['deductions']='Report on Pay Bill Deductions';
$GLOBALS['acc_off']='Mr Maheshbhai chaudhari';
$GLOBALS['cardex']='65';
$GLOBALS['ddo_no']='553';
$GLOBALS['grand']=array();
$GLOBALS['phone']='091-261-2244175';
$GLOBALS['mobile']='091 98244 19535';
$GLOBALS['ministry']='Health';
$GLOBALS['tan']='SRTG01499B';
$GLOBALS['sis_a']='120';

$array_1=prepare_array_1($link,$_POST['bill_group'],$_POST['bill_number'],$rpp);
if(count($array_1)<=0){echo '<h2>No Records. Nothing to print</h2>';exit(0);}
$array_2=prepare_array_2($array_1,$rpp);
$GLOBALS['total_pages']=count($array_2);
$array_3=prepare_array_3($array_2);
$array_4=prepare_array_4($array_3);

ob_start();
print_outer($array_2);
$myStr = ob_get_contents();
ob_end_clean();
//echo $myStr;
//exit(0);

class ACCOUNT extends TCPDF {

	public function Header() 
	{
	}
	
	public function Footer() 
	{
	}	
}

$pdf = new ACCOUNT('L', 'mm', 'A4', true, 'UTF-8', false);
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
$pdf->writeHTML($myStr, true, false, true, false, '');
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

where 		bill_group=\''.$bill_group.'\' 
		and bill_number=\''.$bill_number.'\'
		and (`Pay_of_Officer_0101(-)` + `Pay_of_Establishment_0102(-)`)>0
';
return $sql;

}

function prepare_array_1($link,$bill_group,$bill_number,$rpp)
{
	$sql=mk_sql($bill_group,$bill_number);
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	$tot=mysqli_num_rows($result);
	//echo $tot;
	$array_1=array();
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

//for carry forward
function prepare_array_4($array_3)
{
	$pn=count($array_3);

	for($i=0;$i<$pn;$i++)
	{
			$chunk=array_chunk($array_3,$i+1);
			$query_fields=array_keys($chunk[0][0]);
			foreach($query_fields as $field=>$value)
			{
				$array_4[$i][$value]=array_sum(array_column($chunk[0],$value));
			}
	}
	return $array_4;
}


function print_outer($a)
{
		$aa=prepare_array_3($a);
		$aaa=prepare_array_4($aa);
		
		foreach($a as $page_number=>$page_detail)
		{	
			if($page_number!=0)
			{
				print_minus_page($page_number,$page_detail,$aaa[$page_number],$aaa[$page_number-1],current($a[0])['remark']);
			}
			else
			{
				print_minus_page($page_number,$page_detail,$aaa[$page_number],array(),current($a[0])['remark']);
			}
		}
		//apt-get install php-numbers-words

}



////////////////minus

function print_minus_page($n,$a2,$a3,$prev_a3,$remark)
{
	echo '<h4 align="center">'.$GLOBALS['college'].'</h3>';
	echo '<h4 align="center">Schedule of miscellaneous recovery deductions</h4>';
	echo '<h4 align="center">For the month of '.$remark.'';
	echo ' [Bill: '.$_POST['bill_group'].'-'.$_POST['bill_number'].'] (Page:'.($n+1).')</h4>';
	
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';

		$ded_head='<tr>				
					<th width="10%"><b>Sr</b></th>
					<th width="25%"><b>Name of Emp</b></th>
					<th width="25%"><b>Designation</b></th>
					<th width="25%"><b>Miscellaneous Recovery</b></th>
					<th width="15%"><b>Remark</b></th>
				</tr><tr>
					<th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
				</tr>';
				
		echo $ded_head;				

	if($prev_a3)
	{
		echo_a3_minus($n,$prev_a3,'B/F');	
	}
	foreach($a2 as $salary_number=>$salary_details)
	{
		echo_a1_minus($salary_number,$salary_details);
	}
	
	if($n<($GLOBALS['total_pages']-1))
	{
		echo_a3_minus($n,$a3,'C/F');
	}
	else
	{
		echo_a3_minus($n,$a3,'');
	}
	echo '</table>';
	
	//apt-get install php-numbers-words
	///error may be:  undefined this in --> usr/share/php/Numbers/Words.php on line 97
	//Remove $this as follwos in like 97
	//$truth_table  = ($classname == get_class()) ? 'T' : 'F';

	if($n==($GLOBALS['total_pages']-1))
	{
		echo '<table><tr><td align="right">Total in Words: '.
				Numbers_Words::toWords(($a3['Pay_of_Officer_0101(-)']+$a3['Pay_of_Establishment_0102(-)']),"en_US").' Only</td></tr>
				</table>';
	}
	
	if($n<($GLOBALS['total_pages']-1))
	{
		echo '<h2 style="page-break-after: always;"></h2>';
	}
}

function echo_a1_minus($n,$d)
{
	/*
	+`Pay_of_Officer_0101(-)`
+`Pay_of_Establishment_0102(-)`
* */
		echo '<tr>';
		echo '<td>'.($n+1).'</td>';
		echo '<td>'.$d['fullname'].'</td>';
		echo '<td>'.$d['post'].'</td>';
		echo '<td>'.($d['Pay_of_Officer_0101(-)']+$d['Pay_of_Establishment_0102(-)']).'</td>';
		echo '<td></td>';
		echo '</tr>';
}

function echo_a3_minus($n,$d,$f)
{
			echo '<tr>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td>Total '.$f.'</td>';
		echo '<td>'.($d['Pay_of_Officer_0101(-)']+$d['Pay_of_Establishment_0102(-)']).'</td>';
		echo '<td></td>';
			echo '</tr>';

}


?>

