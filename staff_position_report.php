<?php
session_start();
$nojunk='defined';
require_once 'common.php';
require_once('tcpdf/tcpdf.php');
require_once('Numbers/Words.php');
$link=connect();

//print_r($_POST);

$GLOBALS['college']='Government Medical College, Majura Gate, Surat';

ob_start();
print_position($link,$_POST['bill_group'],$_POST['bill_number']);
$myStr = ob_get_contents();
ob_end_clean();
echo $myStr;
exit(0);


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

function print_position($link,$bg,$bn)
{
	////BillGroup array
	$sql_bg='select 
				count(ns1.staff_id) filled_in_bill_group,
				ns1.data post
			from 
				nonsalary ns1
			where 
				ns1.bill_group=\''.$bg.'\' and 
				ns1.nonsalary_type_id=3
				group by ns1.data
					';
										
	if(!$result_bg=mysqli_query($link,$sql_bg)){mysqli_error($link);return FALSE;}
	$position_bg=array();	
	while($position_ar=mysqli_fetch_assoc($result_bg))
	{
		$position_bg[]=$position_ar;
	}
	//echo '<pre>';print_r($postion_bg);
	
	////Billnumber array
	$sql_bn='select 
				count(ns1.staff_id) filled_in_bill_number,
				ns1.data post,
				ns2.data bill_number 
			from 
				nonsalary ns1, nonsalary ns2 
			where 
				ns1.bill_group=\''.$bg.'\' and 
				ns1.nonsalary_type_id=3 and 
				ns2.nonsalary_type_id=1 and 
				ns1.staff_id=ns2.staff_id and 
				ns1.bill_group=ns2.bill_group and 
				ns2.data=\''.$bn.'\'
				group by ns2.data,ns1.data
					';
										
	if(!$result_bn=mysqli_query($link,$sql_bn)){return FALSE;}
	$position_bn=array();	
	while($position_ar=mysqli_fetch_assoc($result_bn))
	{
		$position_bn[]=$position_ar;
	}
	//echo '<pre>';print_r($postion_bn);
	
	$bill=get_raw($link,'select * from bill_group where bill_group=\''.$bg.'\'');
	
	if(round($bg/1000000,0)==34)
	{
		$sql='select * from post where class=34';
	}
	elseif(round(($bg/1000000),0)==12)
	{
		$sql='select * from post where class=12';
	}
	else {echo round($bg/1000000,0).': unacceptable bill group';return false;}
	
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo '<table border=1 style="border-collapse: collapse;">';
	echo '<tr><th colspan="5"><h3>Statement of Sectioned, Filled and Vacant Post</h3></th></tr>';
	echo '<tr><th colspan="5"><h3>'.$bill['remark'].'  ['.$bg.'-'.$bn.']</h3></th></tr>';
	echo '<tr><th colspan="5"><h3>Government Medical College Surat</h3></th></tr>';
	

	echo '<tr><th>Post</th><th>Senctioned</th><th>Filled</th><th>Vacant</th><th>Filled in the Bill</th></tr>';
	//print_r($position_bg);
	$sum_senctioned=0;
	$sum_filled=0;
	$sum_this_bill=0;
	
	while($ar=mysqli_fetch_assoc($result))
	{
		$x=search_array_of_array($position_bg,'post',$ar['post'],'filled_in_bill_group');
		$y=search_array_of_array($position_bn,'post',$ar['post'],'filled_in_bill_number');
		echo '<tr>
				<td>'.$ar['post'].'</td>
				<td align="center">'.$ar['senctioned'].'</td>';
		echo '<td align="center">'.$x.'</td>';
		echo '<td align="center">'.($ar['senctioned']-$x).'</td>';
		echo '<td align="center">'.$y.'</td>';
		echo '</tr>';
		$sum_senctioned=$sum_senctioned+$ar['senctioned'];
		$sum_filled=$sum_filled+$x;
		$sum_this_bill=$sum_this_bill+$y;		
	}
	echo '<tr><td>Total</td><td align="center">'.$sum_senctioned.'</td><td align="center">'.$sum_filled.'</td>
			<td align="center">'.($sum_senctioned-$sum_filled).'</td><td align="center">'.$sum_this_bill.'</td></tr>';
	echo '</table>';
}

function search_array_of_array($a1,$key1,$value1,$key2)
{
	//print_r($a1);
	foreach($a1 as $v)
	{
		if($v[$key1]==$value1)
		{
			return $v[$key2];
		}
	}
		return false;
}
	//select ns1.staff_id,ns1.data post,ns2.staff_id,ns2.data bill_number from nonsalary ns1, nonsalary ns2 where ns1.bill_group=12170701 and ns1.nonsalary_type_id=3 and ns2.nonsalary_type_id=1 and ns1.staff_id=ns2.staff_id and ns1.bill_group=ns2.bill_group
	
	//select count(ns1.staff_id),ns1.data post,ns2.data bill_number from nonsalary ns1, nonsalary ns2 where ns1.bill_group=12170701 and ns1.nonsalary_type_id=3 and ns2.nonsalary_type_id=1 and ns1.staff_id=ns2.staff_id and ns1.bill_group=ns2.bill_group group by ns2.data,ns1.data
	
	//select count(ns1.staff_id),ns1.data post,ns2.data bill_number from nonsalary ns1, nonsalary ns2 where ns1.bill_group=12170701 and ns1.nonsalary_type_id=3 and ns2.nonsalary_type_id=1 and ns1.staff_id=ns2.staff_id and ns1.bill_group=ns2.bill_group and ns2.data=101 group by ns2.data,ns1.data

?>

