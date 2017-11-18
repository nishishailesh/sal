<?php
session_start();
require_once 'common.php';

//print_r($_POST);
$link=connect();
menu();

/*
if(isset($_POST['bill_group']) && isset($_POST['staff_id']))
{
	if(isset($_POST['action']))
	{
		if($_POST['action']=='E')
		{
			echo '<div align=center style="background-color:lightgray;">';
			echo '<table ><tr><td><h2>Salary Slip of</h2></td><td>';
			display_staff($link,$_POST['staff_id']);
			echo '</td></tr></table>';
			display_bill($link,$_POST['bill_group']);
			edit_nonsalary($link,$_POST['staff_id'],$_POST['bill_group']);
			edit_salary($link,$_POST['staff_id'],$_POST['bill_group']);
			echo '</div>';
		}
		
		if($_POST['action']=='C')
		{
			$staff_detail=get_raw($link, 'select * from staff where staff_id=\''.$_POST['staff_id'].'\'');
			echo '<form method=post>';
			echo '<table align=center class=border style="background-color:lightgreen;">';
			echo '<input type=hidden name=staff_id value=\''.$_POST['staff_id'].'\'>';
			echo '<input type=hidden name=bill_group value=\''.$_POST['bill_group'].'\'>';
			echo '<tr><th>Copy salary of</th><td>'.$staff_detail['fullname'].'</td></tr>';
			echo '<tr><th>From Bill</th><td>'.$_POST['bill_group'].'</td></tr>';
			echo '<tr><th>To Bill</th><td>';
			$sql='select bill_group from bill_group order by bill_group desc';
			mk_select_from_sql($link,$sql,'bill_group','to_bill_group','','');
			echo '</td></tr><tr><td  align=center colspan=2>';
			echo '<input type=submit name=action value=select_bill_group>';
			echo '</td></tr></table></form>';				
		}
		if($_POST['action']=='select_bill_group')
		{
			copy_salary($link,$_POST['staff_id'],$_POST['bill_group'],$_POST['to_bill_group']);
			copy_nonsalary($link,$_POST['staff_id'],$_POST['bill_group'],$_POST['to_bill_group']);
			//echo '<div id=response onclick="showhide(\'spn\');"></div>';
			//display_staff($link,$_POST['staff_id']);
			//display_bill($link,$_POST['to_bill_group']);
			//edit_nonsalary($link,$_POST['staff_id'],$_POST['to_bill_group']);
			//edit_salary($link,$_POST['staff_id'],$_POST['to_bill_group']);				
		}		
		
		if($_POST['action']=='D')
		{
			delete_raw_by_id_dpc($link,'salary','staff_id',$_POST['staff_id'],'bill_group',$_POST['bill_group']);
			delete_raw_by_id_dpc($link,'nonsalary','staff_id',$_POST['staff_id'],'bill_group',$_POST['bill_group']);
		}		
		
	}
	
}
*/

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

