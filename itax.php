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

ob_start();
print_itax($link,$_POST['bill_group'],$_POST['bill_number']);
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


function itax_page_header($link,$bg,$bn,$pg)
{
	$bill_details=get_raw($link,'select * from bill_group where bill_group=\''.$bg.'\'');
	
	echo '<h4 align="center" style="border: 2px solid #000000;">Schedule of Income Tax Deduction (Page:'.$pg.')</h4>';
	echo '<h4 align="center">'.$GLOBALS['college'].'</h3>';
	echo '<h4 align="center">Under Head: 0021 Income Tax</h4>';
	echo '<h4 align="center">For the month of '.$bill_details['remark'].
									' [Bill: '.$bg.
									'-'.$bn.']</h4>';
	
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';

	echo '<tr><td colspan="3">Name of DDO</td><td colspan="2">'.$GLOBALS['acc_off'].'</td>
				<td colspan="3">CARDEX NO</td><td colspan="2">'.$GLOBALS['cardex'].'</td></tr>';
	echo '<tr><td colspan="3">DDO No</td><td colspan="2">'.$GLOBALS['ddo_no'].'</td>
				<td colspan="3">Major Head</td><td colspan="2">2210</td></tr>';
	echo '<tr><td colspan="3">Email</td><td colspan="2"></td>
				<td colspan="3">Name of Department</td><td colspan="2">Health and Family Welfare</td></tr>';
	echo '<tr><td colspan="3">Phone with STD Code</td><td colspan="2">'.$GLOBALS['phone'].'</td>
				<td colspan="3">Mobile No</td><td colspan="2">'.$GLOBALS['mobile'].'</td></tr>';
	echo '<tr><td colspan="3">DDO Reg. No</td><td colspan="2"></td>
				<td colspan="3">Name of Ministry</td><td colspan="2">'.$GLOBALS['ministry'].'</td></tr>';
	echo '<tr><td colspan="10" style="font-size: large;">TAN No.: '.$GLOBALS['tan'].'</td></tr>';
	echo '</table>';
	
}

function print_itax($link,$bg,$bn)
{
		$itax_head='<tr>				
					<th width="5%"><b>Sr</b></th>
					<th width="25%"><b>Name of Emp</b></th>
					<th width="12%"><b>PAN</b></th>
					<th width="8%"><b>Desig</b></th>
					<th width="10%"><b>Gross Amt.</b></th>
					<th width="10%"><b>ITax Ded.</b></th>
					<th width="5%"><b>Surcharge</b></th>
					<th width="5%"><b>Edu Cess(3%)</b></th>
					<th width="10%"><b>Tot. Ded.(6+7+8)</b></th>
					<th width="10%"><b>Remarks</b></th>
				</tr><tr>
					<th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th>
				</tr>';
				
	$s=get_staff_of_a_bill_number_namewise($link,$bg,$bn);

	$sum_itax=0;
	$sum_cess=0;
	$sum_it_no_cess=0;
	$sum_pay=0;
	$count=1;
	itax_page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	echo $itax_head;
	foreach($s as $sr=>$staff_id)
	{
		$staff=get_staff($link,$staff_id);
		
		//$post=get_nsfval($link,$bg,$staff_id,$GLOBALS['post_id']);
		$post_full=get_nsfval($link,$bg,$staff_id,$GLOBALS['post_id']);
		$post['data']=get_short_post($link,$post_full['data']);
		
		$pan=get_nsfval($link,$bg,$staff_id,$GLOBALS['pan_id']);
		
		
		$basic=get_sfval($link,$bg,$staff_id,$GLOBALS['basic_id']);
		$gp=get_sfval($link,$bg,$staff_id,$GLOBALS['gp_id']);
		$basic_e=get_sfval($link,$bg,$staff_id,$GLOBALS['basic_e_id']);
		$gp_e=get_sfval($link,$bg,$staff_id,$GLOBALS['gp_e_id']);
		$npa=get_sfval($link,$bg,$staff_id,$GLOBALS['npa_id']);
		
		//
		//$pay=$basic['amount']+$gp['amount']+$npa['amount']+
		//		$basic_e['amount']+$gp_e['amount'];
		//For Itax full salary neds to be displayed
		$all_sums=find_sums_govt($link,$staff_id,$bg);
		$pay=$all_sums[0];
		
		$itax=get_sfval($link,$bg,$staff_id,$GLOBALS['itax_id']);
		$cess=$itax['amount']*0.03;
		$it_no_cess=$itax['amount']-$cess;

		if($itax['amount']>0)
		{
			echo '<tr>
					<td>'.$count.'</td>				
					<td align="left" >'.$staff['fullname'].'</td>
					<td>'.$pan['data'].'</td>
					<td>'.$post['data'].'</td>
					<td>'.$pay.'</td>			
					<td>'.$it_no_cess.'</td>
					<td></td>
					<td>'.$cess.'</td>
					<td>'.$itax['amount'].'</td>
					<td>'.$itax['remark'].'</td>				
				</tr>';
				
				$sum_itax=$sum_itax+$itax['amount'];
				$sum_cess=$sum_cess+$cess;
				$sum_it_no_cess=$sum_it_no_cess+$it_no_cess;
				$sum_pay=$sum_pay+$pay;
			if($count%$GLOBALS['rpp']==0 && ($count/$GLOBALS['rpp'])>0)
			{
				echo '<tr>	<td></td> <td></td> <td></td> <td>C/F</td>
				<td>'.$sum_pay.'</td>
				<td>'.$sum_it_no_cess.'</td>
				<td></td>
				<td>'.$sum_cess.'</td>
				<td>'.$sum_itax.'</td></tr>';
				
				echo '</table>';
				echo '<h2 style="page-break-after: always;"></h2>';

				itax_page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);				
				echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
				echo $itax_head;
				echo '<tr>	<td></td> <td></td> <td></td> <td>B/F</td>
				<td>'.$sum_pay.'</td>
				<td>'.$sum_it_no_cess.'</td>
				<td></td>
				<td>'.$sum_cess.'</td>
				<td>'.$sum_itax.'</td></tr>';
			}
			$count++;
		}
		
	}
				echo '<tr>	<td></td> <td></td> <td></td> <td>Total</td>
				<td>'.$sum_pay.'</td>
				<td>'.$sum_it_no_cess.'</td>
				<td></td>
				<td>'.$sum_cess.'</td>
				<td>'.$sum_itax.'</td></tr>';

				$xxx=new Numbers_Words();
				echo '<tr><td align="right" colspan="10">Total in Words: '.
				$xxx->toWords($sum_itax,"en_US").' Only</td></tr>';				
				echo '</table>';
}

?>

