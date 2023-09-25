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

	echo '<h4 align="center" style="border: 1px solid #000000;">Schedule pertaining to the credit head 8011 (Insurance Fund Pension fund)</h4>';		
	echo '<h4 align="center">'.$GLOBALS['college'].''.$GLOBALS['address'].''.$GLOBALS['city'].'</h3>';
	echo '<h4 align="center">Under Head: 8009 State Provident Fund</h4>';
	echo '<h4 align="center">Amount deducted from salary for the month of '.$bill_details['remark'].' (Bill: '.$bg.'-'.$bn.')</h4>';
	echo '<h4 align="center">Name of office maintaining accounts: Accountant Genral Rajkot</h4>';	

}
function print_table($link,$bg,$bn)
{

	$di[480]=array('srt'=>'A','group'=>'A(New)(480-1120)','rate'=>480,'emp'=>0);
	$di[240]=array('srt'=>'B','group'=>'B(New)(240-560)','rate'=>240,'emp'=>0);
	$di[120]=array('srt'=>'C','group'=>'C(New)(120-280)','rate'=>120,'emp'=>0);
	$di[60]=array('srt'=>'D','group'=>'D(New)(60-140)','rate'=>60,'emp'=>0);
	
		$head='<tr>				
					<th width="5%"><b>Sr</b></th>
					<th width="25%"><b>Name of Emp</b></th>
					<th width="10%"><b>Group</b></th>
					<th width="20%"><b>Insurance Fund</b></th>
					<th width="20%"><b>Saving Fund</b></th>
					<th width="20%"><b>Total</b></th>
				</tr><tr>
					<th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th>
				</tr>';
				
				
	$s=get_staff_of_a_bill_number($link,$bg,$bn);
	
	$sum_sis_if=0;
	$sum_sis_sf=0;
	
	$count=1;
	page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	echo $head;
	foreach($s as $sr=>$staff_id)
	{
				
		$staff=get_staff($link,$staff_id);
		$emp_name=$staff['fullname'];	
	
		$sis_if=get_sfval($link,$bg,$staff_id,$GLOBALS['sis_if_id']);
		$sis_sf=get_sfval($link,$bg,$staff_id,$GLOBALS['sis_sf_id']);
		
		
		
		if($sis_if['amount']>0)
		{
			$group=$di[$sis_if['amount']]['srt'];
			echo '<tr>
					<td>'.$count.'</td>
					<td align="left">'.$emp_name.'</td>
					<td>'.$group.'</td>
					<td>'.$sis_if['amount'].'</td>
					<td>'.$sis_sf['amount'].'</td>
					<td>'.($sis_if['amount']+$sis_sf['amount']).'</td>
				</tr>';
				
			$sum_sis_if=$sum_sis_if+$sis_if['amount'];
			$sum_sis_sf=$sum_sis_sf+$sis_sf['amount'];
			
			if($count%$GLOBALS['rpp']==0 && ($count/$GLOBALS['rpp'])>0)
			{
			echo '<tr>
					<td></td>
					<td></td>
					<td>C/F</td>
					<td>'.$sum_sis_if.'</td>
					<td>'.$sum_sis_sf.'</td>
					<td>'.($sum_sis_if+$sum_sis_sf).'</td>
				</tr>';
				
				echo '</table>';
				echo '<h2 style="page-break-after: always;"></h2>';

				page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);				
				echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
				echo $head;
			echo '<tr>
					<td></td>
					<td></td>
					<td>B/F</td>
					<td>'.$sum_sis_if.'</td>
					<td>'.$sum_sis_sf.'</td>
					<td>'.($sum_sis_if+$sum_sis_sf).'</td>
				</tr>';
			}
			$count++;
		}
	}
			echo '<tr>
					<td></td>
					<td></td>
					<td>Total</td>
					<td>'.$sum_sis_if.'</td>
					<td>'.$sum_sis_sf.'</td>
					<td>'.($sum_sis_if+$sum_sis_sf).'</td>
				</tr>';
		//$xxx=new Numbers_Words();				
		echo '<tr><td align="right" colspan="9">Total in Words: ';
		my_number_to_words($sum_sis_if+$sum_sis_sf);
		echo ' '.$GLOBALS['n2s'].' Only</td></tr>';
				//$xxx->toWords(($sum_sis_if+$sum_sis_sf),"en_US").' Only</td></tr>';
				
	echo '</table>';
}

?>

