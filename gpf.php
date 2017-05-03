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

//various id numbers as per database
//nonsaLARY
$GLOBALS['gpf_acc_id']=6;
$GLOBALS['post_id']=3;

//SALARY
$GLOBALS['gpf_id']=25;			//non-IV
$GLOBALS['gpf4_id']=26;			//IV
$GLOBALS['gpf_adv_rec_id']=39;	//non-IV
$GLOBALS['gpf4_adv_rec_id']=46;	//non-IV
			
$GLOBALS['basic_e_id']=3;		//est
$GLOBALS['gp_e_id']=4;			//est
$GLOBALS['basic_id']=1;			//12
$GLOBALS['gp_id']=2;			//12
$GLOBALS['npa_id']=5;

ob_start();
print_gpf($link,$_POST['bill_group'],$_POST['bill_number']);
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
$pdf->SetMargins(30, 20, 30);
$pdf->AddPage();
$pdf->writeHTML($myStr, true, false, true, false, '');
$pdf->Output($_POST['bill_group'].'_'.$_POST['bill_number'].'_gpf.pdf', 'I');


function gpf_page_header($link,$bg,$bn,$pg)
{
	$bill_details=get_raw($link,'select * from bill_group where bill_group=\''.$bg.'\'');
	echo '<h4 align="center" style="border: 1px solid #000000;">Schedule of General Providend Fund Deduction</h4>';
	echo '<h4 align="center" >other than Class-IV Page:'.$pg.'</h4>';
			
	echo '<h4 align="center">'.$GLOBALS['college'].'</h3>';
	echo '<h4 align="center">Under Head: 8009 State Provident Fund</h4>';
	echo '<h4 align="center">Amount deducted from salary for the month of '.$bill_details['remark'].' (Bill: '.$bg.'-'.$bn.')</h4>';
	echo '<h4 align="center">Name of office maintaining accounts: Accountant Genral Rajkot</h4>';	
	
}

function print_gpf($link,$bg,$bn)
{

	
	$gpf_head='<tr>				
					<th width="5%"><b>Sr</b></th>
					<th width="14%"><b>GPF A/C No</b></th>
					<th width="18%"><b>Name of Emp</b></th>
					<th width="15%"><b>Desig. Emp.</b></th>
					<th width="10%"><b>Pay</b></th>
					<th width="10%"><b>Monthly sub.</b></th>
					<th width="10%"><b>Adv. Rec.</b></th>
					<th width="9%"><b>Inst No</b></th>
					<th width="9%"><b>Total</b></th>
				</tr>';
				
	//M/DAT/
	//MED
	//PH/
	//PW/
	$s=get_staff_of_a_bill_number($link,$bg,$bn);

	$sum_gpf=0;
	$sum_gpf_ar=0;
	$count=1;
	gpf_page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	echo $gpf_head;
	foreach($s as $sr=>$staff_id)
	{
		$gpf=get_sfval($link,$bg,$staff_id,$GLOBALS['gpf_id']);
		
		$basic=get_sfval($link,$bg,$staff_id,$GLOBALS['basic_id']);
		$gp=get_sfval($link,$bg,$staff_id,$GLOBALS['gp_id']);

		$basic_e=get_sfval($link,$bg,$staff_id,$GLOBALS['basic_e_id']);
		$gp_e=get_sfval($link,$bg,$staff_id,$GLOBALS['gp_e_id']);

		$npa=get_sfval($link,$bg,$staff_id,$GLOBALS['npa_id']);
		
		$pay=$basic['amount']+$gp['amount']+$npa['amount']+
				$basic_e['amount']+$gp_e['amount'];
				
		$acc=get_nsfval($link,$bg,$staff_id,$GLOBALS['gpf_acc_id']);
		$post=get_nsfval($link,$bg,$staff_id,$GLOBALS['post_id']);
		$gpf_ar=get_sfval($link,$bg,$staff_id,$GLOBALS['gpf_adv_rec_id']);
		$staff=get_staff($link,$staff_id);
		
		if($gpf['amount']>0 || $gpf_ar['amount']>0)
		{
			echo '<tr>
					<td width="5%">'.$count.'</td>				
					<td width="14%">'.$acc['data'].'</td>
					<td align="left" width="18%">'.$staff['fullname'].'</td>
					<td width="15%">'.$post['data'].'</td>
					<td width="10%">'.$pay.'</td>				
					<td width="10%">'.$gpf['amount'].'</td>
					<td width="10%">'.$gpf_ar['amount'].'</td>
					<td width="9%">'.$gpf_ar['remark'].'</td>
					<td width="9%">'.($gpf['amount']+$gpf_ar['amount']).'</td>				
				</tr>';
			$sum_gpf=$sum_gpf+$gpf['amount'];
			$sum_gpf_ar=$sum_gpf_ar+$gpf_ar['amount'];
			if($count%$GLOBALS['rpp']==0 && ($count/$GLOBALS['rpp'])>0)
			{
				echo '<tr><td></td> <td></td> <td></td>
				<td></td><td>C/F</td> <td>'.$sum_gpf.'</td>
				<td>'.$sum_gpf_ar.'</td><td></td><td>'.($sum_gpf+$sum_gpf_ar).'</td></tr>';
				
				echo '</table>';
				echo '<h2 style="page-break-after: always;"></h2>';

				gpf_page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);				
				echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
				echo $gpf_head;
				echo '<tr><td></td> <td></td> <td></td>
				<td ></td><td>B/F</td> <td >'.$sum_gpf.'</td>
				
				<td>'.$sum_gpf_ar.'</td><td></td><td>'.($sum_gpf+$sum_gpf_ar).'</td></tr>';
			}
			$count++;
		}
	}
			echo '<tr><td></td> <td></td> <td></td>
			<td></td><td>Total</td> <td>'.$sum_gpf.'</td>
			<td>'.$sum_gpf_ar.'</td><td></td><td>'.($sum_gpf+$sum_gpf_ar).'</td></tr>';	
	echo '</table>';
}

?>

