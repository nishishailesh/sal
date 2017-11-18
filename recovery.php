<?php
session_start();
$nojunk='defined';
require_once 'common.php';
require_once('tcpdf/tcpdf.php');
require_once('Numbers/Words.php');
$link=connect();




//rpp is raw per page
//echo '<pre>';

$GLOBALS['rpp']=15;
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
$GLOBALS['pan_id']=8;

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
$GLOBALS['itax_id']=20;
$GLOBALS['recv_off_id']=28;
$GLOBALS['recv_est_id']=29;
ob_start();
print_recovery($link,$_POST['bill_group'],$_POST['bill_number']);
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


function recovery_page_header($link,$bg,$bn,$pg)
{
	$bill_details=get_raw($link,'select * from bill_group where bill_group=\''.$bg.'\'');


	echo '<h4 align="center">'.$GLOBALS['college'].'</h3>';
	echo '<h4 align="center">Schedule of miscellaneous recovery deductions</h4>';
	echo '<h4 align="center">For the month of '.$bill_details['remark'].'';
	echo ' [Bill: '.$bg.'-'.$bn.'] (Page:'.$pg.')</h4>';
	
}

function print_recovery($link,$bg,$bn)
{
		$head='<tr>				
					<th width="10%"><b>Sr</b></th>
					<th width="25%"><b>Name of Emp</b></th>
					<th width="25%"><b>Designation</b></th>
					<th width="25%"><b>Miscellaneous Recovery</b></th>
					<th width="15%"><b>Remark</b></th>
				</tr><tr>
					<th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
				</tr>';

		//echo '<tr>';
		//echo '<td>'.($n+1).'</td>';
		//echo '<td>'.$d['fullname'].'</td>';
		//echo '<td>'.$d['post'].'</td>';
		//echo '<td>'.($d['Pay_of_Officer_0101(-)']+$d['Pay_of_Establishment_0102(-)']).'</td>';
		//echo '<td></td>';
		//echo '</tr>';	
					
	$s=get_staff_of_a_bill_number($link,$bg,$bn);

	$count=1;
	recovery_page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	echo $head;
	$sum_recv=0;
	
	foreach($s as $sr=>$staff_id)
	{
		$staff=get_staff($link,$staff_id);
		
		//$post=get_nsfval($link,$bg,$staff_id,$GLOBALS['post_id']);
		$post_full=get_nsfval($link,$bg,$staff_id,$GLOBALS['post_id']);
		$post['data']=get_short_post($link,$post_full['data']);
		
		$recv_off=get_sfval($link,$bg,$staff_id,$GLOBALS['recv_off_id']);
		$recv_est=get_sfval($link,$bg,$staff_id,$GLOBALS['recv_est_id']);


		if(($recv_off['amount']+$recv_est['amount'])>0)
		{
			echo '<tr>
					<td>'.$count.'</td>				
					<td align="left" >'.$staff['fullname'].'</td>
					<td>'.$post['data'].'</td>
					<td>'.($recv_off['amount']+$recv_est['amount']).'</td>
					<td>'.$recv_off['remark'].' '.$recv_est['remark'].'</td>				
				</tr>';
				
				$sum_recv=$sum_recv+($recv_off['amount']+$recv_est['amount']);

	
			if($count%$GLOBALS['rpp']==0 && ($count/$GLOBALS['rpp'])>0)
			{
			echo '<tr>
					<td></td>				
					<td align="left" ></td>
					<td>C/F</td>
					<td>'.$sum_recv.'</td>
					<td></td>				
				</tr>';
				
				echo '</table>';
				echo '<h2 style="page-break-after: always;"></h2>';

				recovery_page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);				
				echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
				echo $head;
					echo '<tr>
					<td></td>				
					<td align="left" ></td>
					<td>B/F</td>
					<td>'.$sum_recv.'</td>
					<td></td>				
				</tr>';
			}
			$count++;
		}
	}
				echo '<tr>
				<td></td>				
				<td align="left" ></td>
				<td>Total</td>
				<td>'.$sum_recv.'</td>
				<td></td>				
				</tr>';
		$xxx=new Numbers_Words();
		echo '<tr><td align="right" colspan="5">Total in Words: '.
				$xxx->toWords($sum_recv,"en_US").' Only</td></tr>';
		echo '</table>';
}

?>

