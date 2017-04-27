<?php
session_start();
$nojunk='defined';
require_once 'common.php';
//require_once 'common.js';
require_once('tcpdf/tcpdf.php');
$link=connect();




//rpp is raw per page
$rpp=15;
$GLOBALS['college']='Government Medical College Surat';
$GLOBALS['allowances']='Report on Pay and Allowances Bill';
$GLOBALS['deductions']='Report on Pay Bill Deductions';
$GLOBALS['grand']=array();

$ar=prepare_array($link,$_POST['bill_group'],$_POST['bill_number'],$rpp);
echo '<pre>';
print_r($ar);
print_r($GLOBALS['grand']);
exit(0);

ob_start();
make_table($ar,$rpp);
$myStr = ob_get_contents();
ob_end_clean();
//echo $myStr;


class ACCOUNT extends TCPDF {

	public function Header() 
	{
	}
	
	public function Footer() 
	{
	}	
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
$pdf->SetMargins(30, 10, 30);
$pdf->AddPage();
$pdf->writeHTML($myStr, true, false, true, false, '');
$pdf->Output('example_006.pdf', 'I');



function prepare_array($link,$bill_group,$bill_number,$rpp)
{
	$sql=mk_sql($bill_group,$bill_number);
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	$tot=mysqli_num_rows($result);
	//echo $tot;
	$page=1;
	$count=0;
	while($result_array=mysqli_fetch_assoc($result))
		{	
			$count++;
			$final[$page][$count]=$result_array;
			if($count==$rpp || $count+(($page-1)*$rpp)==$tot)
			{
				foreach($final[$page][1] as $key=>$value)
				{
					$final[$page][$count+1][$key]=array_sum(array_column($final[$page],$key));
					$GLOBALS['grand'][$page][$key]=$final[$page][$count+1][$key];
				}
				$count=0;$page++;
			}
		}
		
	$gr_count=count($GLOBALS['grand']);
	foreach($GLOBALS['grand'][1] as $gk=>$gv)
	{
		$GLOBALS['grand'][$gr_count+1][$gk]=array_sum(array_column($GLOBALS['grand'],$gk));
	}
	return $final;
}


function prepare_grand_total($link,$bill_group,$bill_number,$rpp)
{
	$sql=mk_sql($bill_group,$bill_number);
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	//$tot=mysqli_num_rows($result);
	//echo $tot;

}


function make_table($final,$rpp)
{
	foreach($final as $k=>$pg)
	{
		/////////
		$tot_rec_in_page=count($pg);
		echo '<table cellpadding="1" cellspacing="1" border="0.5" style="text-align:center;">';
		$count=1;
		echo '<tr >	<th colspan="2">Bill No:'.$pg[1]['bill_group'].'('.$pg[1]['bill_number'].')</th>
					<th colspan="12" style="font-size: xx-large;">'.$GLOBALS['college'].'----'.$GLOBALS['allowances'].'</th>
					<th colspan="2">'.$pg[1]['remark'].', Page No. ('.$k.')</th>		
			</tr>';
		$plus_head='		<tr>
					<th width="3%"><b>Sr</b></th>
					<th width="15%"><b>Emp Name<br>Dept,Post<br>7th Pay, 6th Pay</b></th>
					<th width="5%"><b>(002)GP<br>Special<br>FP</b></th>
					<th width="7%"><b>(001)(002)<br>P.Off<br>P.Est</b></th>
					<th width="5%"><b>(128)<br>NPA</b></th>
					<th width="5%"><b>(002)<br>LS/LE</b></th>
					<th width="5%"><b>(005)<br>DA</b></th>
					<th width="5%"><b>(006)<br>HRA</b></th>
					<th width="5%"><b>(016)<br>CLA</b></th>
					<th width="5%"><b>(009)<br>MA</b></th>
					<th width="5%"><b>(013)<br>TA</b></th>
					<th width="5%"><b>(104)<br>BA<br>Ceiling</b></th>
					<th width="5%"><b>(132)WA<br>(131)Uni<br>(129)Nur</b></th>
					<th width="7%"><b>Gross<br>Amt.</b></th>
					<th width="5%"><b>For Audit<br>Only</b></th>
					<th width="5%"><b>(510)ITx<br>(520)Sur</b></th>
				</tr><tr>
					<th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th>
				</tr>';
				
		echo $plus_head;
		foreach($pg as $data)
		{
			echo '<tr>';
			echo_one_raw_p($data,$count,$rpp,$k,$tot_rec_in_page);
			echo '</tr>';		
			$count++;
		}
		
		echo '</table>';
		echo '<h2 style="page-break-after: always;"></h2>';
		
		////////////////////
		echo '<table cellpadding="1" cellspacing="1" border="0.5" style="text-align:center;">';
		$count=1;
		echo '<tr >	<th colspan="2">Bill No:'.$pg[1]['bill_group'].'('.$pg[1]['bill_number'].')</th>
					<th colspan="12" style="font-size: xx-large;">'.$GLOBALS['college'].'----'.$GLOBALS['deductions'].'</th>
					<th colspan="2">'.$pg[1]['remark'].', Page No. ('.$k.')</th>		
			</tr>';
			
		$ded_head='<tr>				
					<th width="3%"><b>Sr</b></th>
					<th width="5%"><b>(550)<br>HRR</b></th>
					<th width="5%"><b>(570)<br>Prof<br>Tax</b></th>
					<th width="5%"><b>(581)<br>SIS I/F<br>1981</b></th>
					<th width="5%"><b>(582)<br>SIS S/F<br>1981</b></th>
					<th width="5%"><b>(620)<br>GPF<br>non-IV</b></th>
					<th width="5%"><b>()<br>CPF</b></th>
					<th width="5%"><b>(013)<br>FES<br>Adv.</b></th>
					<th width="5%"><b>(014)<br>Food<br>Adv.</b></th>
					<th width="5%"><b>(592)<br>Car/Sct<br>Adv.</b></th>
					<th width="5%"><b>HBA<br>(590)Pri<br>(760)Int</b></th>
					<th width="5%"><b>(531)<br>GPF<br>CL-IV</b></th>
					<th width="5%"><b>Recv.</b></th>
					<th width="7%"><b>Tot.<br>Ded.</b></th>
					<th width="7%"><b>Net<br>Amt.</b></th>
					<th width="9%"><b>GPF<br>CPF<br>No</b></th>
				</tr><tr>
					<th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>31</th><th>32</th>
				</tr>';
				
		echo $ded_head;				
		
		foreach($pg as $data)
		{
			echo '<tr>';
			echo_one_raw_m($data,$count,$rpp,$k,$tot_rec_in_page);
			echo '</tr>';		
			$count++;
		}
		echo '</table>';
		echo '<h2 style="page-break-after: always;"></h2>';
		
		
	}
	
		////////////
		
		echo '<table cellpadding="1" cellspacing="1" border="0.5" style="text-align:center;">';	
				$plus_head='		<tr>
					<th width="3%"><b></b></th>
					<th width="15%"><b>Page</b></th>
					<th width="5%"><b>(002)GP<br>Special<br>FP</b></th>
					<th width="7%"><b>(001)(002)<br>P.Off<br>P.Est</b></th>
					<th width="5%"><b>(128)<br>NPA</b></th>
					<th width="5%"><b>(002)<br>LS/LE</b></th>
					<th width="5%"><b>(005)<br>DA</b></th>
					<th width="5%"><b>(006)<br>HRA</b></th>
					<th width="5%"><b>(016)<br>CLA</b></th>
					<th width="5%"><b>(009)<br>MA</b></th>
					<th width="5%"><b>(013)<br>TA</b></th>
					<th width="5%"><b>(104)<br>BA<br>Ceiling</b></th>
					<th width="5%"><b>(132)WA<br>(131)Uni<br>(129)Nur</b></th>
					<th width="7%"><b>Gross<br>Amt.</b></th>
					<th width="5%"><b>For Audit<br>Only</b></th>
					<th width="5%"><b>(510)ITx<br>(520)Sur</b></th>
				</tr><tr>
					<th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th>
				</tr>';
				
		echo $plus_head;
		
		foreach($GLOBALS['grand'] as $pkk=>$pvv)
		{
			echo '<tr>';
			echo_one_raw_p($pvv,$pkk,20,1,count($GLOBALS['grand']));
			echo '</tr>';		
		}
		
		
		echo '</table>';
		echo '<h2 style="page-break-after: always;"></h2>';

		//////////////
		echo '<table cellpadding="1" cellspacing="1" border="0.5" style="text-align:center;">';	
			$ded_head='<tr>				
					<th width="3%"><b>Page</b></th>
					<th width="5%"><b>(550)<br>HRR</b></th>
					<th width="5%"><b>(570)<br>Prof<br>Tax</b></th>
					<th width="5%"><b>(581)<br>SIS I/F<br>1981</b></th>
					<th width="5%"><b>(582)<br>SIS S/F<br>1981</b></th>
					<th width="5%"><b>(620)<br>GPF<br>non-IV</b></th>
					<th width="5%"><b>()<br>CPF</b></th>
					<th width="5%"><b>(013)<br>FES<br>Adv.</b></th>
					<th width="5%"><b>(014)<br>Food<br>Adv.</b></th>
					<th width="5%"><b>(592)<br>Car/Sct<br>Adv.</b></th>
					<th width="5%"><b>HBA<br>(590)Pri<br>(760)Int</b></th>
					<th width="5%"><b>(531)<br>GPF<br>CL-IV</b></th>
					<th width="5%"><b>Recv.</b></th>
					<th width="7%"><b>Tot.<br>Ded.</b></th>
					<th width="7%"><b>Net<br>Amt.</b></th>
					<th width="9%"><b>GPF<br>CPF<br>No</b></th>
				</tr><tr>
					<th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>31</th><th>32</th>
				</tr>';
				
		echo $ded_head;			
		foreach($final as $k=>$pg)
		{
			
			echo '<tr>';
			echo_one_raw_m($pg[count($pg)],count($pg),$rpp,$k,$tot_rec_in_page);
			echo '</tr>';		
		}
		echo '</table>';
		echo '<h2 style="page-break-after: always;"></h2>';		
}

function echo_one_raw_m($d,$count,$rpp,$page,$tt)
{
	if($count<$tt)
	{
		$sq=($count+(($page-1)*$rpp));
		echo '<td>'.$sq.'<br><br></td>';
	}
	else
	{
		echo '<td>Page<br>('.$page.')<br>Total</td>';
	}
	
	echo '<td>'.$d['Rent_of_Building_9560(-)'].'</td>';
	echo '<td>'.$d['Professional_Tax_9570(-)'].'</td>';
	echo '<td>'.$d['SIS_I_9581(-)'].'</td>';
	echo '<td>'.$d['SIS_S_9582(-)'].'</td>';
	echo '<td>'.$d['GPF_non_IV_9670(-)'].'</td>';
	echo '<td>'.$d['CPF_9690(-)'].'</td>';
	echo '<td>'.$d['Festival_A_5701(-)'].'</td>';
	echo '<td>'.$d['Food_Grains_A_5801(-)'].'</td>';
	echo '<td>'.$d['Car_A_9741(-)'].'</td>';
	echo '<td>'.$d['HBA_9591(-)'].'</td>';
	echo '<td>'.$d['GPF_IV_9531(-)'].'</td>';
		
	if($count<$tt)
	{
		echo '<td>'.$d['Pay_of_Officer_0101(-)'].'<br>'.$d['Pay_of_Establishment_0102(-)'].'</td>';
	}
	else
	{
		echo '<td>'.($d['Pay_of_Officer_0101(-)']+$d['Pay_of_Establishment_0102(-)']).'</td>';
	}
	echo '<td>'.$d['deduction'].'</td>';
	echo '<td>'.$d['net'].'</td>';
	
	if($count<$tt)
	{
		echo '<td>'.$d['gpf_acc'].'<br>'.$d['cpf_acc'].'</td>';
	}
	else
	{
		echo '<td></td>';
	}

}

function echo_one_raw_p($d,$count,$rpp,$page,$tt)
{
	
	if($count<$tt)
	{
		if($d['Grade_Pay_of_Officer_0101(+)']>0){$gp=$d['Grade_Pay_of_Officer_0101(+)'];}
		else($gp=$d['Grade_Pay_of_Establishment_0102(+)']);
		
		$sq=($count+(($page-1)*$rpp));
		echo 
			'<td>'.$sq.'</td>	
			<td>'.
				$d['fullname'].'<br>'.
				substr($d['department'],0,10).','.substr($d['post'],0,4).'<br>'.
				$d['pay_scale'].','.$d['old_pay_scale'].
			'</td>
			<td>'
					.$gp.'<br>'		
					.$d['Special_Post_Allow_0104(+)'].'<br>'
					.$d['Family_Welfare_Allow_0104(+)'].
			'</td>';
		echo '<td>';
		echo $d['Pay_of_Officer_0101(+)'].'<br>'.
			 $d['Pay_of_Establishment_0102(+)'];
		echo '</td>';
		echo '<td>';
		echo $d['NPA_0128(+)'].'<br>';
		echo '</td>';	
		echo 	'<td>'.
				$d['Leave_Salary_Encash_0109(+)']
				.'</td>';		
		echo 	'<td>'.
				$d['Dearness_Allowance_0103(+)']
				.'</td>';
		echo 	'<td>'.
				$d['House_Rent_Allowance_0110(+)']
				.'</td>';
		echo 	'<td>'.
				$d['Compansatory_Local_Allowance_0111(+)']
				.'</td>';				
		echo 	'<td>'.
				$d['Medical_Allowance_0107(+)']
				.'</td>';	
		echo 	'<td>'.
				$d['Transport_Allowance_0113(+)']
				.'</td>';	
		echo 	'<td>'.
				$d['BA_0104(+)'].'<br>'.$d['Ceiling_Extra_0104(+)']
				.'</td>';
		echo 	'<td>'.
				$d['Washing_Allowance_0132(+)'].'<br>'.$d['Uniform_Allowance_0131(+)'].'<br>'.$d['Nursing_Allownace_0129(+)']
				.'</td>';		
		echo 	'<td>'.
				$d['gross']
				.'</td>';	
		echo 	'<td></td>';
		echo 	'<td>'.
				$d['Income_Tax_9510(-)']
				.'</td>';
	}
	else
	{
		echo '	<td></td>
				<td>Page<br>('.$page.')<br>Total</td>
				<td>'.
				($d['Grade_Pay_of_Officer_0101(+)'] +
				$d['Grade_Pay_of_Establishment_0102(+)']+
				$d['Special_Post_Allow_0104(+)']+
				$d['Family_Welfare_Allow_0104(+)']
				)
				.'</td>';
				
		echo 	'<td>'.
				($d['Pay_of_Officer_0101(+)'] +	$d['Pay_of_Establishment_0102(+)'])
				.'</td>';
		echo 	'<td>'.
				$d['NPA_0128(+)']
				.'</td>';
		echo 	'<td>'.
				$d['Leave_Salary_Encash_0109(+)']
				.'</td>';
		echo 	'<td>'.
				$d['Dearness_Allowance_0103(+)']
				.'</td>';
		echo 	'<td>'.
				$d['House_Rent_Allowance_0110(+)']
				.'</td>';
		echo 	'<td>'.
				$d['Compansatory_Local_Allowance_0111(+)']
				.'</td>';	
		echo 	'<td>'.
				$d['Medical_Allowance_0107(+)']
				.'</td>';	
		echo 	'<td>'.
				$d['Transport_Allowance_0113(+)']
				.'</td>';
		echo 	'<td>'.
				($d['BA_0104(+)']+$d['Ceiling_Extra_0104(+)'])
				.'</td>';
		echo 	'<td>'.
				($d['Washing_Allowance_0132(+)'] + $d['Uniform_Allowance_0131(+)'] + $d['Nursing_Allownace_0129(+)'])
				.'</td>';				
		echo 	'<td>'.
				$d['gross']
				.'</td>';
		echo 	'<td></td>';
		echo 	'<td>'.
				$d['Income_Tax_9510(-)']
				.'</td>';
		
	}
}

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


?>

