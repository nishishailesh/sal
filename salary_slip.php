<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
			
<?php
session_start();
//$nojunk='yes';
set_time_limit(360);
require_once 'common.php';
require_once('tcpdf/tcpdf.php');
//require_once('Numbers/Words.php');
//print_r($_POST);
class ACCOUNT extends TCPDF {

	public function Header() 
	{
	}
	
	public function Footer() 
	{
	}	
}

function print_one_nonsalary_slip($link,$staff_id,$bill_group,$format_table='')
{
	if(strlen($format_table)==0){$format_table='nonsalary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$count=0;
	while($ar=mysqli_fetch_assoc($result))
	{
		$dt=get_raw($link,'select * from nonsalary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\' and 
								nonsalary_type_id=\''.$ar['nonsalary_type_id'].'\'');
		$title=$ar['nonsalary_type_id'];							 
		if($count%3==0){$t='<tr>';}else{$t='';}
		if($count%3==2){$tt='</tr>';}else{$tt='';}
		
		$ptbl=$ptbl.$t.'<td>'.$ar['name'].'</td>
										<td>'.$dt['data'].'</td>'.$tt;
		$count=$count+1;
	}
	

	$tbl='<table  width="100%" align=center id=nonsal class=border style="background-color:lightgray;display=block;">'.$ptbl.'</table>';
			
	echo $tbl;
}


function print_one_salary_slip($link,$staff_id,$bill_group,$format_table='')
{
	if(strlen($format_table)==0){$format_table='salary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$mtbl='';
	while($ar=mysqli_fetch_assoc($result))
	{
		$dt=get_raw($link,'select * from salary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\' and 
								salary_type_id=\''.$ar['salary_type_id'].'\'');
								
		if($ar['type']=='+'){$ptbl=$ptbl.'<tr>
										<td width="65%">'.$ar['name'].'</td>
										<td width="15%">'.$dt['amount'].'</td>
										<td width="20%">'.$dt['remark'].'</td></tr>';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'<tr>
										<td width="65%">'.$ar['name'].'</td>
										<td width="15%">'.$dt['amount'].'</td>
										<td width="20%">'.$dt['remark'].'</td></tr>';}	
	}
	
	$tbl='<table width="100%" align=center id=sal class=border style="display=block;background-color:#A0CA94">	
				<tr><th>Payment</th><th>Deductions</th></tr>
				<tr><td valign=top><table class=table width="100%">'.$ptbl.'</table>
				</td><td valign=top><table class=table width="100%">'.$mtbl.'</table></td></tr>

		</table>';
			
	echo $tbl;
	$pmn=find_sums($link,$staff_id,$bill_group);

	echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
	   <table class="table table-striped "><tr><td align=center>';
	echo '<table width="100%" class="table table-striped " align="center" style="display:block;background:lightpink;"><tr>';
	echo '<th>Gross</th><th>Deductions</th><th>Net</th></tr><tr>';
	echo '<th>'.$pmn[0].'</th><th>'.$pmn[1].'</th><th>'.$pmn[2].'</th>';
	echo '</tr></table>';
	
	echo '</td></tr></table><div><div></div>';
}


ob_start();

$link=connect();

if(isset($_POST['action']))
{
	if($_POST['action']=='print_salary')
	{
		//echo 'read bill group';
		get_bill_group($link);
	}

	elseif($_POST['action']=='select_bill_group')
	{
		$sql='select distinct nonsalary.staff_id,fullname from nonsalary,staff where 
						bill_group=\''.$_POST['bill_group'].'\' 
						and 
						nonsalary.staff_id=staff.staff_id order by fullname';
											
		if(!$result=mysqli_query($link,$sql)){return FALSE;}

		$tot=mysqli_num_rows($result);
		$count=1;
		while($result_array=mysqli_fetch_assoc($result))
		{
			echo '<table border="0" align="center">';
			echo '<tr><td>';
				echo '<h3 align="center">Salary Slip,'.$GLOBALS['college'].''.$GLOBALS['city'].'</h3>';
			echo '</td></tr>';
			echo '<tr><td>';
				echo '<h3 align="right">'.$result_array['fullname'].'</h3>';
			echo '</td></tr>';
			echo '<tr><td>';
				echo '<table align="center" ><tr><td>';
				display_staff($link,$result_array['staff_id']);
				echo '</td><td>';
			 	display_bill($link,$_POST['bill_group']);
				echo '</td></tr></table>';
			echo '</td></tr>';
			echo '<tr><td width="100%">';		
			print_one_nonsalary_slip($link,$result_array['staff_id'],$_POST['bill_group']);
			echo '</td></tr>';
			echo '<tr><td>';			
			print_one_salary_slip($link,$result_array['staff_id'],$_POST['bill_group']);
			echo '</td></tr>';
			echo '<tr><td>';			
				//echo '<br><br><br><br><br><br><br>';	
				echo '<table border="0" align="right">';
				echo '<tr><td align="center">Account Officer</td></tr>';
				echo '<tr><td align="center">'.$GLOBALS['college'].'</td></tr>';
				echo '<tr><td align="center">'.$GLOBALS['city'].'</td></tr>';
				echo '</table>';
			echo '</td></tr>';
			echo '</table>';
			if($count<$tot)
			{
				echo '<h2 style="page-break-after: always;"></h2>';
			}
			$count++;
		}

		$myStr = ob_get_contents();
		ob_end_clean();

		echo $myStr;
		exit(0);

		//$pdf = new ACCOUNT('P', 'mm', 'A4', true, 'UTF-8', false);
		////$pdf->SetFont('dejavusans', '', 9); 	//big file size and time and memory
		////	public function SetFont($family, $style='', $size=null, $fontfile='', $subset='default', $out=true) {
		//$pdf->SetFont('courier', '', 9);		//smaller size better performance
		////$pdf->SetFont('times', '', 9);		//smaller size better performance
		//$pdf->SetMargins(20, 20, 20);
		//$pdf->AddPage();
		//$pdf->writeHTML($myStr, true, false, true, false, '');
		//$pdf->Output($_POST['bill_group'].'_'.$_POST['bill_number'].'_salary_slip.pdf', 'I');
	}
}

?>

