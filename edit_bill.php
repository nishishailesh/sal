<?php
session_start();
require_once 'common.php';

//print_r($_POST);

$link=connect();
menu();

if(isset($_POST['action']))
{
	if($_POST['action']=='edit_bill')
	{
		echo '<div align=center style="background-color:#FFD4D4;">';
		get_bill_group($link);
		echo '</div>';
	}
	if($_POST['action']=='E')
	{
		echo '<div align=center style="background-color:lightgray;">';
		display_staff($link,$_POST['staff_id']);
		edit_nonsalary($link,$_POST['staff_id'] ,$_POST['bill_group']);
		edit_salary($link,$_POST['staff_id'] ,$_POST['bill_group']);
		echo '</div>';
	}
	
	if($_POST['action']=='D')
	{
		delete_raw_by_id_dpc($link,'salary','staff_id',$_POST['staff_id'],'bill_group',$_POST['bill_group']);
		delete_raw_by_id_dpc($link,'nonsalary','staff_id',$_POST['staff_id'],'bill_group',$_POST['bill_group']);
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
		echo '<input type=submit name=action value=select_to_bill>';
		echo '</td></tr></table></form>';				
	}
	if($_POST['action']=='select_to_bill')
	{
		copy_salary($link,$_POST['staff_id'],$_POST['bill_group'],$_POST['to_bill_group']);
		copy_nonsalary($link,$_POST['staff_id'],$_POST['bill_group'],$_POST['to_bill_group']);				
	}		
				
}

if(isset($_POST['bill_group']))
{
	echo '<div align=center style="background-color:#FFD4D4;">';
	list_bill($link,$_POST['bill_group']);
	echo '</div>';
}


?>
