<?php
session_start();
$nojunk='defined';
require_once 'common.php';
require_once('tcpdf/tcpdf.php');
//require_once('Numbers/Words.php');
$link=connect();




//rpp is raw per page
//echo '<pre>';

$GLOBALS['rpp']=20;
$GLOBALS['total_pages']='';
$GLOBALS['allowances']='Report on Pay and Allowances Bill';
$GLOBALS['deductions']='Report on Pay Bill Deductions';
$GLOBALS['cardex']='65';
$GLOBALS['ddo_no']='553';
$GLOBALS['grand']=array();
$GLOBALS['phone']='091-261-2244175';
$GLOBALS['mobile']='091 98244 19535';
$GLOBALS['ministry']='Health';
$GLOBALS['tan']='SRTG01499B';
//various id numbers as per database
//nonsaLARY
$GLOBALS['gpf_acc_id']=6;
$GLOBALS['post_id']=3;
$GLOBALS['qtr']=9;
$GLOBALS['bill_number_id']=1;

//SALARY
$GLOBALS['gpf_id']=25;			//non-IV
$GLOBALS['gpf4_id']=26;			//IV
$GLOBALS['gpf_adv_rec_id']=39;	//non-IV
$GLOBALS['gpf4_adv_rec_id']=46;	//IV
			
$GLOBALS['basic_e_id']=3;		//est
$GLOBALS['gp_e_id']=4;			//est
$GLOBALS['basic_id']=1;			//12
$GLOBALS['gp_id']=2;			//12
$GLOBALS['npa_id']=5;
$GLOBALS['rob']=21;
$GLOBALS['ptax_id']=22;
$GLOBALS['sis_if_id']=23;
$GLOBALS['sis_sf_id']=24;

ob_start();
print_table($link,$_POST['bill_group'],$_POST['bill_number']);
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
				$this->SetY(-10);
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

	}	
}

$pdf = new ACCOUNT('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetFont('dejavusans', '', 9);
$pdf->SetMargins(30, 20, 30);
$pdf->AddPage();
$pdf->writeHTML($myStr, true, false, true, false, '');
$pdf->Output($_POST['bill_group'].'_'.$_POST['bill_number'].'_gpf.pdf', 'I');


function page_header($link,$bg,$bn,$pg)
{
	$bill_details=get_raw($link,'select * from bill_group where bill_group=\''.$bg.'\'');

	echo '<h4 align="center" style="border: 2px solid #000000;">Schedule pertaining to the credit head 8011 (Insurance Fund Pension fund), Annexure - C (Refer Para :2)</h4>';
	echo '<h4 align="center">'.$GLOBALS['college'].''.$GLOBALS['address'].''.$GLOBALS['city'].'</h3>';
	echo '<h4 align="center">Under Head: 8011 Insurance and Pension Fund</h4>';
	echo '<h4 align="center">Gujarat Government Employees Group Insurance Scheme -1981</h4>';
	echo '<h4 align="center">For the month of '.$bill_details['remark'].'';
	echo ' [Bill: '.$bg.'-'.$bn.']</h4>';
	
	

}

function prepare_sis_summary($link,$bg,$bn)
{
	/*
	SELECT 
s1.staff_id,s1.bill_group,nonsalary.data bill_number,
s1.amount,s2.amount

FROM salary s1,salary s2,nonsalary  WHERE 
s1.bill_group='34170501' and 
nonsalary.nonsalary_type_id=1 and nonsalary.data='5' and


(s1.salary_type_id=23 or  s1.salary_type_id=24) and
(s2.salary_type_id=23 or  s2.salary_type_id=24) and
(s1.salary_type_id!= s2.salary_type_id) and


s1.bill_group=s2.bill_group and 
s1.bill_group=nonsalary.bill_group  and 

s1.staff_id=nonsalary.staff_id and
s2.staff_id=nonsalary.staff_id

group by staff_id
*/

	$sql=
	'SELECT 
		s1.staff_id,s1.bill_group,nonsalary.data bill_number,
		s1.amount sis_i,s2.amount sis_s

		FROM salary s1,salary s2,nonsalary  
		WHERE 
		s1.bill_group=\''.$bg.'\' and 
		nonsalary.nonsalary_type_id=\''.$GLOBALS['bill_number_id'].'\' and nonsalary.data=\''.$bn.'\' and

		(s1.salary_type_id=23 or  s1.salary_type_id=24) and
		(s2.salary_type_id=23 or  s2.salary_type_id=24) and
		(s1.salary_type_id!= s2.salary_type_id) and

		s1.bill_group=s2.bill_group and 
		s1.bill_group=nonsalary.bill_group  and 

		s1.staff_id=nonsalary.staff_id and
		s2.staff_id=nonsalary.staff_id';

		if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}

		$grp=array(
		array('A',	'Old',	24,	56,	0,	0),
		array('A',	'New',	120,280,0,	0),
		array('B',	'Old',	12, 28,	0,	0),
		array('B',	'New',	60,	140,0,	0),
		array('C',	'Old',	06,	14,	0,	0),
		array('C',	'New',	30, 70,	0,	0),
		array('D',	'Old',	03,	07,	0,	0),
		array('D',	'New',	15,	35,	0,	0)
	);
			
		while($ar=mysqli_fetch_assoc($result))
		{
			foreach($grp as &$gg)
			{
				if($gg[2]==$ar['sis_i'] && $gg[3]==$ar['sis_s'])
				{
					$gg[5]=$gg[5]+1;
				}
				elseif($gg[2]==$ar['sis_i'] && $gg[3]!=$ar['sis_s'])
				{
					$gg[4]=$gg[4]+1;
				}
			}		
		}
		
		$sum_g4=0;
		$sum_g5=0;
		$sum_sis_i=0;
		$sum_sis_s=0;
		
		foreach($grp as $g)
		{
			$cl='Group:'.$g[0].' '.$g[1].'('.$g[2].'-'.$g[3].')';
			$sis_i=($g[4]*$g[2])+($g[5]*$g[2]);
			$sis_s=($g[4]*$g[3])+($g[5]*$g[3]);
			echo '<tr>';
			echo '<td>'.$cl.'</td>';
			echo '<td>'.$g[4].'</td>';
			echo '<td>'.$g[5].'</td>';
			echo '<td>'.($g[4]+$g[5]).'</td>';
			echo '<td>'.$sis_i.'</td>';
			echo '<td>'.$sis_s.'</td>';
			echo '<td>'.($sis_i+$sis_s).'</td>';
			echo '<td></td>';	
			echo '</tr>';
			$sum_g4=$sum_g4+$g[4];
			$sum_g5=$sum_g5+$g[5];
			$sum_sis_i=$sum_sis_i+$sis_i;
			$sum_sis_s=$sum_sis_s+$sis_s;			
		}
			echo '<tr>';
			echo '<th>Grand Total</th>';
			echo '<td>'.$sum_g4.'</td>';
			echo '<td>'.$sum_g5.'</td>';
			echo '<td>'.($sum_g4+$sum_g5).'</td>';
			echo '<td>'.$sum_sis_i.'</td>';
			echo '<td>'.$sum_sis_s.'</td>';
			echo '<td>'.($sum_sis_i+$sum_sis_s).'</td>';
			echo '<td></td>';	
			echo '</tr>';		
		//$xxx=new Numbers_Words();
		echo '<tr><td align="right" colspan="8">Total in Words:';
		 my_number_to_words($sum_sis_i+$sum_sis_s);
		echo ' '.$GLOBALS['n2s'].' Only</td></tr>';		
		
}			


function print_table($link,$bg,$bn)
{

	
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';

		$head='<tr>				
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
				
	page_header($link,$bg,$bn,'');	//no page number required
	
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	echo $head;

	prepare_sis_summary($link,$bg,$bn);
				
	echo '</table>';
}

?>

