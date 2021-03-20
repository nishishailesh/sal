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
$GLOBALS['cpf_acc_id']=7;
$GLOBALS['post_id']=3;

//SALARY
$GLOBALS['gpf_id']=25;	
$GLOBALS['cpf_id']=27;			//non-IV
$GLOBALS['gpf4_id']=26;			//IV
$GLOBALS['gpf_adv_rec_id']=39;	//non-IV
$GLOBALS['gpf4_adv_rec_id']=46;	//non-IV
			
$GLOBALS['basic_e_id']=3;		//est
$GLOBALS['gp_e_id']=4;			//est
$GLOBALS['basic_id']=1;			//12
$GLOBALS['gp_id']=2;			//12
$GLOBALS['npa_id']=5;
$GLOBALS['da_id']=7;	

ob_start();
print_cpf($link,$_POST['bill_group'],$_POST['bill_number']);
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


function cpf_page_header($link,$bg,$bn,$pg)
{
	$bill_details=get_raw($link,'select * from bill_group where bill_group=\''.$bg.'\'');
	echo '<h4 align="center" style="border: 1px solid #000000;">Schedule of General Providend Fund Deduction</h4>';
	echo '<h4 align="center">other than Class-IV Page:'.$pg.'</h4>';
			
	echo '<h4 align="center">'.$GLOBALS['college'].''.$GLOBALS['address'].''.$GLOBALS['city'].'</h3>';
	echo '<h4 align="center">Under Head: 8009 State Provident Fund</h4>';
	echo '<h4 align="center">Amount deducted from salary for the month of '.$bill_details['remark'].' (Bill: '.$bg.'-'.$bn.')</h4>';
	echo '<h4 align="center">Name of office maintaining accounts: Accountant Genral Rajkot</h4>';	
	
}

function print_cpf($link,$bg,$bn)
{

	
	$cpf_head='<tr>				
					<th width="5%"><b>Sr</b></th>
					<th width="14%"><b>CPF A/C No</b></th>
					<th width="23%"><b>Name of Emp</b></th>
					<th width="15%"><b>Mobile No.</b></th>
					<th width="5%"><b>Desig.<br> Emp.</b></th>
					<th width="8%"><b>Basic</b></th>
					<th width="10%"><b>DA</b></th>
					<th width="10%"><b>CPF Amount</b></th>
			        	<th width="10%"><b>NET Amount</b></th>	
				</tr>';
				
	//M/DAT/
	//MED
	//PH/
	//PW/
	$s=get_staff_of_a_bill_number($link,$bg,$bn);

	$sum_cpf=0;
	
	$count=1;
	cpf_page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);
	echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	echo $cpf_head;
	foreach($s as $sr=>$staff_id)
	{
		$cpf=get_sfval($link,$bg,$staff_id,$GLOBALS['cpf_id']);
		
		$basic=get_sfval($link,$bg,$staff_id,$GLOBALS['basic_id']);
		$gp=get_sfval($link,$bg,$staff_id,$GLOBALS['gp_id']);

		$basic_e=get_sfval($link,$bg,$staff_id,$GLOBALS['basic_e_id']);
		$gp_e=get_sfval($link,$bg,$staff_id,$GLOBALS['gp_e_id']);

		$da=get_sfval($link,$bg,$staff_id,$GLOBALS['da_id']);
		
                $net_amount=find_sums($link,$staff_id,$bg);
	        $net=$net_amount[2];
		//This is for CPF
		//$pay=$basic['amount']+$gp['amount']+$npa['amount']+
		//		$basic_e['amount']+$gp_e['amount'];
		//For GPF full salary is to be displayed
		$all_sums=find_sums_govt($link,$staff_id,$bg);
		$pay=$all_sums[0];
				
		$acc=get_nsfval($link,$bg,$staff_id,$GLOBALS['cpf_acc_id']);
		
		//$post=get_nsfval($link,$bg,$staff_id,$GLOBALS['post_id']);
		$post_full=get_nsfval($link,$bg,$staff_id,$GLOBALS['post_id']);
		$post['data']=get_short_post($link,$post_full['data']);
		
		//$gpf_ar=get_sfval($link,$bg,$staff_id,$GLOBALS['gpf_adv_rec_id']);
		$staff=get_staff($link,$staff_id);
		//$staff=get_raw($link,'select moblie from staff where staff_id=\''.$staff_id.'\'');
		//$staffdetail=get_
		if($cpf['amount']>0) 
		{
			echo '<tr>
					<td>'.$count.'</td>				
					<td>'.$acc['data'].'</td>
					<td align="left">'.$staff['fullname'].'</td>
				    <td> '.$staff['mobile'].'</td>
					<td>'.$post['data'].'</td>
					<td>'.$basic['amount'].'</td>	
					<td>'.$da['amount'].'</td>	
					<td>'.$cpf['amount'].'</td>
					<td>'.$net.'</td>						
				</tr>';
			$sum_cpf=$sum_cpf+$cpf['amount'];
			
			if($count%$GLOBALS['rpp']==0 && ($count/$GLOBALS['rpp'])>0)
			{
				echo '<tr><td></td> <td></td> <td></td>
				<td></td><td></td><td></td><td>C/F</td> <td>'.$sum_cpf.'</td>
				</tr>';
				
				echo '</table>';
				echo '<h2 style="page-break-after: always;"></h2>';

				cpf_page_header($link,$bg,$bn,round(($count/$GLOBALS['rpp']),0)+1);				
				echo '<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
				echo $cpf_head;
				echo '<tr><td></td><td></td> <td></td> <td></td><td></td>
				<td ></td><td>B/F</td><td >'.$sum_cpf.'</td></tr>';
			}
			$count++;
		}
	}
			echo '<tr><td></td> <td></td> <td></td><td></td>
			<td></td><td></td><td>Total</td> <td>'.$sum_cpf.'</td>
			</tr>';	
		   //$xxx=new Numbers_Words();
		   echo '<tr><td></td><td align="right" colspan="9">Total in Words: ';
		    my_number_to_words($sum_cpf);
		   echo ' '.$GLOBALS['n2s'].' Only</td></tr>';
			//$xxx->toWords(($sum_gpf+$sum_gpf_ar),"en_US").' Only</td></tr>';
	echo '</table>';
}

?>

