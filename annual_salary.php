<?php
session_start();
require_once 'common.php';

//print_r($_POST);
$link=connect();
menu();


echo '<div align=center style="background-color:#FFD4D4;">';
list_annual_salary($link,$_POST['staff_id'],$_POST['year']);
echo '</div>';



function print_one_h_salary($link,$staff_id,$bill_group,$format_table='')
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
								
		if($ar['type']=='+'){$ptbl=$ptbl.'<td>'.$dt['amount'].'</td>';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'<td>'.$dt['amount'].'</td>';}	
	}
	
	$tbl=$ptbl.$mtbl;
			
	
	
	$pmn=find_sums($link,$staff_id,$bill_group);

	
	
	$summary_column='<th>'.$pmn[0].'</th><th>'.$pmn[1].'</th><th>'.$pmn[2].'</th>';
		
	echo $summary_column.$tbl;
}

function print_one_h_salary_header($link,$format_table='')
{
	if(strlen($format_table)==0){$format_table='salary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$mtbl='';
	
	
	while($ar=mysqli_fetch_assoc($result))
	{				
		if($ar['type']=='+'){$ptbl=$ptbl.'<td>(+)'.$ar['name'].'</td>';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'<td>(-)'.$ar['name'].'</td>';}	
	}
	
	$tbl=$ptbl.$mtbl;
			
	echo $tbl;
}

function list_annual_salary($link,$staff_id,$year)
{
			echo '<table ><tr><td><h2>Annual Salary of</h2></td><td>';
			display_staff($link,$_POST['staff_id']);
			echo '</td></tr></table>';
			
	$sql='select distinct bill_group from salary where staff_id=\''.$staff_id.'\' order by bill_group desc';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}	
	echo '<table align=center class=border style="background-color:#ADD8E6">';
	echo '<tr><th>Bill Group</th><th>Type</th><th>Remark</th><th>Gross</th><th>Deduction</th><th>Net</th>';
	print_one_h_salary_header($link,'');
	echo '</tr>';
			
	while($bg=mysqli_fetch_assoc($result))
	{
		if(substr($bg['bill_group'],2,2)==$year)
		{
		$ar=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
		
			echo '<tr>
			<td>'.$ar['bill_group'].'</td>
			<td>'.$ar['bill_type'].'</td>
			<td>'.$ar['remark'].'</td>';
			print_one_h_salary($link,$staff_id,$bg['bill_group'],'');
			echo '</tr>';
		}
	}
	echo '</table>';

}

?>

