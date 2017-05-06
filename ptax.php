<?php
session_start();
$nojunk='defined';
require_once 'common.php';
require_once('tcpdf/tcpdf.php');
require_once('Numbers/Words.php');
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

	echo '<h4 align="center" style="border: 2px solid #000000;">Schedule of Professional Tax Deduction (Page:'.$pg.')</h4>';
	echo '<h4 align="center">'.$GLOBALS['college'].'</h3>';
	echo '<h4 align="center">Under Head: 0028 Professional Tax</h4>';
	echo '<h4 align="center">For the month of '.$bill_details['remark'].'</h4>';
	echo '<h4 align="center"> Bill: '.$_POST['bill_group'].'-'.$_POST['bill_number'].'</h4>';
}
function print_table($link,$bg,$bn)
{

		$head='<tr>				
					<th width="10%"><b>Sr</b></th>
					<th width="25%"><b>Name of Emp</b></th>
					<th width="25%"><b>Designation</b></th>
					<th width="20%"><b>Gross Amt.</b></th>
					<th width="20%"><b>P.Tax</b></th>
				</tr><tr>
					<th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
				</tr>';
				
				
	$s=get_staff_of_a_bill_number($link,$bg,$bn);
	
	$sum_ptax=0;
	$count=1;
	page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	echo $head;
	foreach($s as $sr=>$staff_id)
	{
		$derived=find_sums($link,$staff_id,$bg);
		
		$staff=get_staff($link,$staff_id);
		$emp_name=$staff['fullname'];	

		$post=get_nsfval($link,$bg,$staff_id,$GLOBALS['post_id']);
		
		$gross=$derived[0];
	
		$ptax=get_sfval($link,$bg,$staff_id,$GLOBALS['ptax_id']);
		
		if($ptax['amount']>0)
		{
			echo '<tr>
					<td>'.$count.'</td>
					<td align="left">'.$emp_name.'</td>
					<td>'.$post['data'].'</td>
					<td>'.$gross.'</td>
					<td>'.$ptax['amount'].'</td>
				</tr>';
			$sum_ptax=$sum_ptax+$ptax['amount'];
			
			if($count%$GLOBALS['rpp']==0 && ($count/$GLOBALS['rpp'])>0)
			{
			echo '<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>C/F</td>
					<td>'.$sum_ptax.'</td>
				</tr>';
				
				echo '</table>';
				echo '<h2 style="page-break-after: always;"></h2>';

				page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);				
				echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
				echo $head;
			echo '<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>B/F</td>
					<td>'.$sum_ptax.'</td>
				</tr>';
			}
			$count++;
		}
	}
			echo '<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>Total</td>
					<td>'.$sum_ptax.'</td>
				</tr>';
		echo '<tr><td align="right" colspan="9">Total in Words: '.
				Numbers_Words::toWords($sum_ptax,"en_US").' Only</td></tr>';
				
	echo '</table>';
}

?>

