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

ob_start();
$a=modify_a($array_1);
print_sis_c($a);
//echo '<pre>';
//print_r($a);
//sis_c_rows($a);
	
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
		and `SIS_I_9581(-)`>0
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


function print_sis_c($a)
{

	echo '<h4 align="center" style="border: 2px solid #000000;">Schedule pertaining to the credit head 8011 (Insurance Fund Pension fund), Annexure - C (Refer Para :2)</h4>';
	echo '<h4 align="center">'.$GLOBALS['college'].'</h3>';
	echo '<h4 align="center">Under Head: 8011 Insurance and Pension Fund</h4>';
	echo '<h4 align="center">Gujarat Government Employees Group Insurance Scheme -1981</h4>';
	echo '<h4 align="center">For the month of '.$a[0]['remark'].'';
	echo ' [Bill: '.$_POST['bill_group'].'-'.$_POST['bill_number'].']</h4>';
	
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';

		$ded_head='<tr>				
					<th width="20%"><b>Group and Rate</b></th>
					<th width="10%"><b>Total Number of Emp under I/F only</b></th>
					<th width="10%"><b>Total Number of Emp under I/F + S/F</b></th>
					<th width="10%"><b>Total Number of Emp (2+3)</b></th>
					<th width="10%"><b>Cont. to I/F</b></th>
					<th width="10%"><b>Cont. to S/F</b></th>
					<th width="10%"><b>Total Cont</b></th>
					<th width="20%"><b>Remarks</b></th>
				</tr><tr>
					<th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th>
				</tr>';
				
		echo $ded_head;				
		
		sis_c_rows($a);
		
	echo '</table>';

}

function modify_a($a)
{
	foreach($a as $sr=>&$sal)
	{
		if($sal['SIS_I_9581(-)']>0 && $sal['SIS_S_9582(-)']>0)
		{
			$sal['is']='is';
			$sal['sis_group']=get_group($sal['SIS_I_9581(-)'],'di');
		}
		elseif($sal['SIS_I_9581(-)']>0 && $sal['SIS_S_9582(-)']==0)
		{
			$sal['is']='i';
			$sal['sis_group']=get_group($sal['SIS_I_9581(-)'],'di');
		}
		elseif($sal['SIS_I_9581(-)']==0 && $sal['SIS_S_9582(-)']>0)
		{
			$sal['is']='s';
			$sal['sis_group']=get_group($sal['SIS_S_9582(-)'],'ds');
		}		
		elseif($sal['SIS_I_9581(-)']==0 && $sal['SIS_S_9582(-)']==0)
		{
			$sal['is']='';
			$sal['sis_group']='';
		}		
	}
	//echo '<pre>';
	//print_r($a);
	return $a;
}

function get_group($ded,$ar)
{
	$di[24]=array('group'=>'A(Old)(24-56)','rate'=>24,'emp'=>0);
	$di[120]=array('group'=>'A(New)(120-280)','rate'=>120,'emp'=>0);
	$di[12]=array('group'=>'B(Old)(12-28)','rate'=>12,'emp'=>0);
	$di[60]=array('group'=>'B(New)(60-140)','rate'=>60,'emp'=>0);
	$di[6]=array('group'=>'C(Old)(6-14)','rate'=>6,'emp'=>0);
	$di[30]=array('group'=>'C(New)(30-70)','rate'=>30,'emp'=>0);
	$di[3]=array('group'=>'D(Old))(3-7)','rate'=>3,'emp'=>0);
	$di[15]=array('group'=>'D(New)(15-35)','rate'=>15,'emp'=>0);

	$ds[56]=array('group'=>'A(Old)(24-56)','rate'=>56,'emp'=>0);
	$ds[280]=array('group'=>'A(New)(120-280)','rate'=>280,'emp'=>0);
	$ds[28]=array('group'=>'B(Old)(12-28)','rate'=>28,'emp'=>0);
	$ds[140]=array('group'=>'B(New)(60-140)','rate'=>140,'emp'=>0);
	$ds[14]=array('group'=>'C(Old)(6-14)','rate'=>14,'emp'=>0);
	$ds[70]=array('group'=>'C(New)(30-70)','rate'=>70,'emp'=>0);
	$ds[7]=array('group'=>'D(Old)(3-7)','rate'=>7,'emp'=>0);
	$ds[35]=array('group'=>'D(New)(15-35)','rate'=>35,'emp'=>0);	
	if($ar=='di')
	{
		return $di[$ded]['group'];
	}
	if($ar=='ds')
	{
		return $ds[$ded]['group'];
	}	
}

function sis_c_rows($a)
{
	//echo '<pre>';
	//print_r($a);

		$final=array();
		
		$di[24]=array('group'=>'A(Old)(24-56)','rate'=>24,'emp'=>0);
		$di[120]=array('group'=>'A(New)(120-280)','rate'=>120,'emp'=>0);
		$di[12]=array('group'=>'B(Old)(12-28)','rate'=>12,'emp'=>0);
		$di[60]=array('group'=>'B(New)(60-140)','rate'=>60,'emp'=>0);
		$di[6]=array('group'=>'C(Old)(6-14)','rate'=>6,'emp'=>0);
		$di[30]=array('group'=>'C(New)(30-70)','rate'=>30,'emp'=>0);
		$di[3]=array('group'=>'D(Old))(3-7)','rate'=>3,'emp'=>0);
		$di[15]=array('group'=>'D(New)(15-35)','rate'=>15,'emp'=>0);
		
		foreach($di as $gr=>$grv)
		{
			foreach($a as $sr=>$sal)
			{
				if(!isset($final[$grv['group']]['is_emp'])){$final[$grv['group']]['is_emp']=0;}
				if(!isset($final[$grv['group']]['s_emp'])){$final[$grv['group']]['s_emp']=0;}
				if(!isset($final[$grv['group']]['i_emp'])){$final[$grv['group']]['i_emp']=0;}
				if(!isset($final[$grv['group']]['i_cont'])){$final[$grv['group']]['i_cont']=0;}
				if(!isset($final[$grv['group']]['s_cont'])){$final[$grv['group']]['s_cont']=0;}			
				
				if($sal['sis_group']==$grv['group'])
				{
					if($sal['is']=='is')
					{
						$final[$grv['group']]['is_emp']=$final[$grv['group']]['is_emp'] +1;
					}
					elseif($sal['is']=='i')
					{
						$final[$grv['group']]['is_emp']=$final[$grv['group']]['i_emp'] +1;
					}				
					elseif($sal['is']=='s')
					{
						$final[$grv['group']]['is_emp']=$final[$grv['group']]['s_emp'] +1;
					}		
					$final[$grv['group']]['i_cont']  =$final[$grv['group']]['i_cont'] +$sal['SIS_I_9581(-)'];
					$final[$grv['group']]['s_cont']  =$final[$grv['group']]['s_cont'] +$sal['SIS_S_9582(-)'];
				}
			}
		}

	//print_r($final);
	
	foreach($final as $gr=>$grd)
	{
		echo '<tr>';
		echo '<td align="left">Group:'.$gr.'</td>';
		echo '<td>'.$grd['i_emp'].'</td>';
		echo '<td>'.$grd['is_emp'].'</td>';
		echo '<td>'.$grd['is_emp'].'</td>';		
		echo '<td>'.$grd['i_cont'].'</td>';		
		echo '<td>'.$grd['s_cont'].'</td>';		
		echo '<td>'.($grd['i_cont']+$grd['s_cont']).'</td>';
		echo '<td></td>';		
		echo '</tr>';
		
	}
	
		$i_g_total=array_sum(array_column($final,'i_cont'));
		$s_g_total=array_sum(array_column($final,'s_cont'));
		$gg_total=$i_g_total+$s_g_total;
		echo '<tr>
					<td>Grand total</td>
					<td>'.array_sum(array_column($final,'i_emp')).'</td>
					<td>'.array_sum(array_column($final,'is_emp')).'</td>					
					<td>'.array_sum(array_column($final,'is_emp')).'</td>										
					<td>'.array_sum(array_column($final,'i_cont')).'</td>
					<td>'.array_sum(array_column($final,'s_cont')).'</td>
					<td>'.$gg_total.'</td>';
		echo '</tr>';
		echo '<tr><td align="right" colspan="8">'.Numbers_Words::toWords($gg_total,"en_US").' Only</td></tr>';
	
}
/*
 
 
 
 
 
 
 
 
function sis_c_rows($a)
{
	$di[24]=array('group'=>'A(Old)','rate'=>24,'emp'=>0);
	$di[120]=array('group'=>'A(New)','rate'=>120,'emp'=>0);
	$di[12]=array('group'=>'B(Old)','rate'=>12,'emp'=>0);
	$di[60]=array('group'=>'B(New)','rate'=>60,'emp'=>0);
	$di[6]=array('group'=>'C(Old)','rate'=>6,'emp'=>0);
	$di[30]=array('group'=>'C(New)','rate'=>30,'emp'=>0);
	$di[3]=array('group'=>'D(Old)','rate'=>3,'emp'=>0);
	$di[15]=array('group'=>'D(New)','rate'=>15,'emp'=>0);

	$ds[56]=array('group'=>'A(Old)','rate'=>56,'emp'=>0);
	$ds[280]=array('group'=>'A(New)','rate'=>280,'emp'=>0);
	$ds[28]=array('group'=>'B(Old)','rate'=>28,'emp'=>0);
	$ds[140]=array('group'=>'B(New)','rate'=>140,'emp'=>0);
	$ds[14]=array('group'=>'C(Old)','rate'=>14,'emp'=>0);
	$ds[70]=array('group'=>'C(New)','rate'=>70,'emp'=>0);
	$ds[7]=array('group'=>'D(Old)','rate'=>7,'emp'=>0);
	$ds[35]=array('group'=>'D(New)','rate'=>35,'emp'=>0);	
	
	$if=array_count_values(array_column($a,'SIS_I_9581(-)'));
	foreach($if as $rate=>$number)
	{
		$di[$rate]['emp']=$number;
	}
	$sf=array_count_values(array_column($a,'SIS_S_9582(-)'));
	foreach($sf as $rate=>$number)
	{
		$ds[$rate]['emp']=$number;
	}
	
	//echo '<pre>';
	//print_r($di);
	//print_r($ds);
	//echo '</pre>';
	$g_total='';
	foreach($di as $rate => $ar)
	{
		
		foreach($ds as $k=>$v)
		{
			if($v['group']==$ar['group'])
			{
				$this_ds=$k;
				//echo '<h1>'.$k.'</h1>';
			}
		}
		$if_total=($ar['emp']*$ar['rate']);
		$is_total=($ds[$this_ds]['emp']*$ds[$this_ds]['rate']);
		$g_total=$if_total+$is_total;
		
		//$rw=array('emp_if
		echo '<tr>';
		echo '<td>Group:'.$ar['group'].'('.$ar['rate'].'-'.$ds[$this_ds]['rate'].')</td>';
		echo '<td>'.$ar['emp'].'</td>';
		echo '<td>'.$ds[$this_ds]['emp'].'</td>';
		echo '<td>'.$ar['emp'].'</td>';		
		echo '<td>'.$if_total.'</td>';		
		echo '<td>'.$is_total.'</td>';		
		echo '<td>'.$g_total.'</td>';
		echo '<td></td>';		
		echo '</tr>';
	}



		$if_g_total=array_sum(array_column($a,'SIS_I_9581(-)'));
		$sf_g_total=array_sum(array_column($a,'SIS_S_9582(-)'));
		$gg_total=$if_g_total+$sf_g_total;
		echo '<tr><td align="right" colspan="8">'.Numbers_Words::toWords($gg_total,"en_US").' Only</td></tr>';
				

}
*/

?>

