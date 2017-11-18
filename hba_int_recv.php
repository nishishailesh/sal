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
$GLOBALS['hba_p_id']=33;
$GLOBALS['hba_i_id']=40;

ob_start();
print_form($link,$_POST['bill_group'],$_POST['bill_number']);
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
	echo '<h4 align="center" style="border: 1px solid #000000;">Schedule of Recovery of HBA interest</h4>';	
	echo '<h4 align="center">'.$GLOBALS['college'].'</h3>';
	echo '<h4 align="center">Amount deducted from salary for the month of '.$bill_details['remark'].' (Bill: '.$bg.'-'.$bn.')</h4>';
	
	echo '<table>
	<tr><td>To be filled by Drawing Officer</td><td>To be filled by Treasurey Officer</td></tr>
	<tr><td>District:66</td><td>Voucher No:</td></tr>
	<tr><td>Drawing Officer:553</td><td>Month and Year:</td></tr>
	<tr><td></td><td>Major Head:</td></tr>
	</table>';
}

function print_form($link,$bg,$bn)
{
/*
	$ac_detail=null;		
	$ac_sql='select * from advance where 
				staff_id=\''.$_POST['staff_id'].'\' and type=\''.$_POST['type'].'\'';
	$ac_detail=get_raw($link,$ac_sql);		
			
	//Array ( [tot_amount] => 12000 [tot_inst] => 3 ) 
	$sums_hba=get_hba_total($link,$_POST['staff_id'],$_POST['type'],$_POST['account_number'])	;

*/
	
	$head='<tr>				
					<th width="5%"><b>Sr</b></th>
					<th width="26%"><b>Name of Emp</b></th>
					<th width="10%"><b>Acc No</b></th>
					<th width="5%"><b>Month of Adv</b></th>
					<th width="10%"><b>Amt. of Total Adv</b></th>
					<th width="7%"><b>Total Inst.</b></th>
					<th width="10%"><b>Amt. of Interest</b></th>
					<th width="7%"><b>Inst. No</b></th>
					<th width="10%"><b>total Recv.</b></th>
					<th width="10%"><b>Balance Outstanding</b></th>
				</tr>';

	$s=get_staff_of_a_bill_number($link,$bg,$bn);

	$sum_hba_int_recv=0;
	
	$count=1;
	page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	echo $head;
	foreach($s as $sr=>$staff_id)
	{
		$staff=get_staff($link,$staff_id);
		$hba_int_recv=get_sfval($link,$bg,$staff_id,$GLOBALS['hba_i_id']);
	
		if($hba_int_recv['amount']>0)
		{
			$ac_detail=null;		
			$ac_sql='select * from advance where 
					staff_id=\''.$staff['staff_id'].'\' and type=\'interest\'';
			$ac_detail=get_raw($link,$ac_sql);	
			//Array ( [tot_amount] => 12000 [tot_inst] => 3 ) 
			$sums_hba=get_hba_total($link,$staff['staff_id'],'interest',$ac_detail['account_number'],$bg)	;
		
			echo '<tr>
					<td>'.$count.'</td>				
					<td align="left">'.$staff['fullname'].'</td>
					<td>'.$ac_detail['account_number'].'</td>
					<td></td>
					<td>'.$ac_detail['amount'].'</td>
					<td>'.$ac_detail['installment'].'</td>
					<td>'.$hba_int_recv['amount'].'</td>
					<td>'.($sums_hba['tot_inst']+$ac_detail['pre_installment']).'</td>
					<td>'.($ac_detail['pre_amount']+$sums_hba['tot_amount']).'</td>
					<td>'.($ac_detail['amount']-$ac_detail['pre_amount']-$sums_hba['tot_amount']).'</td>
				</tr>';
				
			$sum_hba_int_recv=$sum_hba_int_recv+$hba_int_recv['amount'];
			if($count%$GLOBALS['rpp']==0 && ($count/$GLOBALS['rpp'])>0)
			{
			echo '<tr>
					<td></td>				
					<td align="left"></td>
					<td></td>
					<td></td>
					<td></td>
					<td>C/F</td>
					<td>'.$sum_hba_int_recv.'</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>';
				
				echo '</table>';
				echo '<h2 style="page-break-after: always;"></h2>';

				page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);				
				echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
				echo $head;
			echo '<tr>
					<td></td>				
					<td align="left"></td>
					<td></td>
					<td></td>
					<td></td>
					<td>B/F</td>
					<td>'.$sum_hba_int_recv.'</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>';
			}
			$count++;
		}
	}
			echo '<tr>
					<td></td>				
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>Total</td>
					<td>'.$sum_hba_int_recv.'</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>';	
		$xxx=new Numbers_Words();
		echo '<tr><td align="right" colspan="10">Total in Words: '.
				$xxx->toWords($sum_hba_int_recv,"en_US").' Only</td></tr>';				
	echo '</table>';
}

?>

