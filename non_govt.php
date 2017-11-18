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
$GLOBALS['gmcs_soc_id']=42;
$GLOBALS['lic_id']=43;
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
	echo '<h4 align="center" style="border: 1px solid #000000;">Schedule of non Governemnt Deductions</h4>';	
	echo '<h4 align="center">'.$GLOBALS['college'].'</h3>';
	echo '<h4 align="center">Amount deducted from salary for the month of '.$bill_details['remark'].' (Bill: '.$bg.'-'.$bn.')</h4>';
	
}

function print_form($link,$bg,$bn)
{

	
	$head='<tr>				
					<th width="10%"><b>Sr</b></th>
					<th width="21%"><b>Name of Emp</b></th>
					<th width="15%"><b>Net Amt.</b></th>
					<th width="12%"><b>GMCS Soc</b></th>
					<th width="12%"><b>LIC</b></th>
					<th width="15%"><b>Total Ded.</b></th>
					<th width="15%"><b>Net Payable</b></th>
				</tr>';

	$s=get_staff_of_a_bill_number($link,$bg,$bn);

	$sum_gmcs_soc=0;
	$sum_lic=0;
	$sum_sums_all=0;
	$sum_sums_govt=0;
	
	$count=1;
	page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	echo $head;
	foreach($s as $sr=>$staff_id)
	{
		$staff=get_staff($link,$staff_id);
		$gmcs_soc=get_sfval($link,$bg,$staff_id,$GLOBALS['gmcs_soc_id']);
		$lic=get_sfval($link,$bg,$staff_id,$GLOBALS['lic_id']);
		$sums_all=find_sums($link,$staff_id,$bg);
		$sums_govt=find_sums_govt($link,$staff_id,$bg);
		
		//if(($gmcs_soc['amount']+$lic['amount'])>0)
		//{
			echo '<tr>
					<td>'.$count.'</td>				
					<td align="left">'.$staff['fullname'].'</td>
					<td>'.$sums_govt[2].'</td>
					<td>'.$gmcs_soc['amount'].'</td>
					<td>'.$lic['amount'].'</td>
					<td>'.($gmcs_soc['amount']+$lic['amount']).'</td>
					<td>'.$sums_all[2].'</td>
				</tr>';
			$sum_gmcs_soc=$sum_gmcs_soc+$gmcs_soc['amount'];
			$sum_lic=$sum_lic+$lic['amount'];
			$sum_sums_all=$sum_sums_all+$sums_all[2];
			$sum_sums_govt=$sum_sums_govt+$sums_govt[2];
			
			if($count%$GLOBALS['rpp']==0 && ($count/$GLOBALS['rpp'])>0)
			{
			echo '<tr>
					<td></td>				
					<td>C/F</td>
					<td>'.$sum_sums_govt.'</td>
					<td>'.$sum_gmcs_soc.'</td>
					<td>'.$sum_lic.'</td>
					<td>'.($sum_gmcs_soc+$sum_lic).'</td>
					<td>'.$sum_sums_all.'</td>
				</tr>';
				
				echo '</table>';
				echo '<h2 style="page-break-after: always;"></h2>';

				page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);				
				echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
				echo $head;
			echo '<tr>
					<td></td>				
					<td>B/F</td>
					<td>'.$sum_sums_govt.'</td>
					<td>'.$sum_gmcs_soc.'</td>
					<td>'.$sum_lic.'</td>
					<td>'.($sum_gmcs_soc+$sum_lic).'</td>
					<td>'.$sum_sums_all.'</td>
				</tr>';
			}
			$count++;
		//}
	}
			echo '<tr>
					<td></td>				
					<td>Total</td>
					<td>'.$sum_sums_govt.'</td>
					<td>'.$sum_gmcs_soc.'</td>
					<td>'.$sum_lic.'</td>
					<td>'.($sum_gmcs_soc+$sum_lic).'</td>
					<td>'.$sum_sums_all.'</td>
				</tr>';
		//echo '<tr><td align="right" colspan="10">Total in Words: '.
		//		Numbers_Words::toWords(($sum_gmcs_soc+$sum_lic),"en_US").' Only</td></tr>';				
	echo '</table>';
}

?>

