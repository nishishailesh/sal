<?php
session_start();
$nojunk='defined';
require_once 'common.php';
//require_once 'common_js.php';
require_once('tcpdf/tcpdf.php');
require_once('Numbers/Words.php');

class ACCOUNT extends TCPDF {

	public function Header() 
	{
	}
	
	public function Footer() 
	{
	}	
}


//print_r($_POST);

$link=connect();
//menu();

ob_start();
print_one_salary_slip($link,157,'12170401');
$myStr = ob_get_contents();
ob_end_clean();

$pdf = new ACCOUNT('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetFont('dejavusans', '', 9);
$pdf->SetMargins(20, 20, 20);
$pdf->AddPage();
$pdf->writeHTML($myStr, true, false, true, false, '');
$pdf->Output('salary_slip.pdf', 'I');


function print_one_salary_slip($link,$staff_id,$bill_group)
{
	$slr=get_raw($link,'select * from salary where staff_id=\''.$staff_id.'\' and bill_group=\''.$bill_group.'\'');
	
	if($slr===FALSE || count($slr)<=0){return false;}
		
	echo 	'<table border="1" align="left" cellspacing="0" cellpadding="1">
				<tr>
					<th colspan="3">Government Medical college Surat</th>';
	echo 		'</tr>
				<tr>';
	echo 			'<th>'.$slr['fullname'].'</th><th>'.$slr['department'].'</th><th>'.$slr['post'].'</th>';
	echo		'</tr>';

	echo 		'<tr>';
	echo 			'<th>QTR:'.$slr['quarter'].'</th>';						
	echo 			'<th>'.$slr['bank'].':'.$slr['bank_acc_number'].'</th>';
	echo			'<th>ID/AADHAR:'.$slr['staff_id'].'</th>';
	echo		'</tr>';
	
	echo 		'<tr>';
	echo 			'<th>GPF:'.$slr['gpf_acc'].'</th>';	
	echo 			'<th>CPF:'.$slr['cpf_acc'].'</th>';	
	echo 			'<th>PAN:'.$slr['pan'].'</th>';
	echo 		'</tr>';
	echo 		'<tr>';
	echo			'<th>Bill:'.$slr['bill_group'].'-'.$slr['bill_number'].'</th>';
	echo			'<th>Bill Type:'.$slr['bill_type'].'</th>';
	echo 			'<th>'.mysql_to_india_date($slr['from_date']).' to '.mysql_to_india_date($slr['to_date']).'</th>';
	echo 		'</tr>';						
	echo 						'<tr><th align="left" colspan="3" >Remark: '.$slr['remark'].'</th>';
	echo			'</tr>';	
	echo 	'</table>';
	
	echo '<table>
	<tr>
		<td>
	
			<table border="1" cellspacing="0" cellpadding="1">';
	
						$exclude=array('fullname','department','post','bank','bank_acc_number','bill_group',
						'budget_head','gpf_acc','cpf_acc','staff_id','remark',
						'staff_position_id','pan','quarter','bill_number','from_date','to_date','bill_type','pay_scale','old_pay_scale');

						$plus=0;
						$minus=0;
	foreach ($slr as $key=>$value)
	{
		if(!in_array($key,$exclude))
		{				
			if($key=='Income_Tax_9510(-)')
			{echo '</table></td><td><table border="1" cellspacing="0" cellpadding="1">';}
			
			echo 		'<tr><td width="70%"><b>'.substr($key,0,-8).'</b></td><td width="30%" align="right">'.$value.'</td></tr>';
			
			if(substr($key,-3)=='(+)'){$plus=$plus+$value;}
			elseif(substr($key,-3)=='(-)'){$minus=$minus+$value;}
		}
	}	
	
	echo '</table></td></tr></table>';
	
	/*
//	echo '<table class=border align=center>';
		echo '<tr><td>Gross</td><td style="text-align:right;" >'.$plus.'</td><td>Deduction</td><td style="text-align:right;" >'.$minus.'</td></tr>';
	echo '<tr><td colspan=2>';
	
	echo '<form method=post>
		<input type=submit name=submit value=refresh>
		<input type=hidden name=staff_id value=\''.$_POST['staff_id'].'\'>
		<input type=hidden name=bill_group value=\''.$_POST['bill_group'].'\'>';
	echo '</form>';

	echo '</td><td>Net</td><td  style="text-align:right;" >'.($plus-$minus).'</td></tr>';
	
//	echo '</table>';
	echo '</td></tr></table>';
	*/
	
}

//get_multiple_staff_id($link);

/*
function get_multiple_staff_id($link)
{
	$sql='select staff_id,fullname,uid from staff
	order by fullname';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$counter=1;
	echo '<form method=post>';
	echo '<input type=submit name=action value=print>';
	echo '<table class=border>';
	while($ar=mysqli_fetch_assoc($result))
	{
		echo '<tr><td>'.$counter.'</td><td><input 
										type=checkbox 
										name=\''.$ar['staff_id'].'\'
										>'.$ar['fullname'].'</td><td>'.$ar['staff_id'].'</td><td>'.$ar['uid'].'</td></tr>';
										
		$counter++;
	}
	echo '</table>';
	echo '</form>';
}
*/


?>

