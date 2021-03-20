<?php
session_start();
require_once 'common.php';

//print_r($_POST);

$link=connect();
head();
menu($link);


if(isset($_POST['action']))
{
	if($_POST['action']=='edit_bill')
	{
		echo '<div >';
		get_bill_group($link);
		echo '</div>';
	}
	
	if($_POST['action']=='E')
	{
		echo '<div >';
		display_staff($link,$_POST['staff_id']);
		edit_nonsalary($link,$_POST['staff_id'] ,$_POST['bill_group']);
		edit_salary($link,$_POST['staff_id'] ,$_POST['bill_group']);
		echo '</div>';
	}
	
	if($_POST['action']=='D')
	{
		$locked=is_bill_group_locked($link,$_POST['bill_group']);
		if($locked!=0){echo $_POST['bill_group'].' Bill Group locked';}
		else
		{
			delete_raw_by_id_dpc($link,'salary','staff_id',$_POST['staff_id'],'bill_group',$_POST['bill_group']);
			delete_raw_by_id_dpc($link,'nonsalary','staff_id',$_POST['staff_id'],'bill_group',$_POST['bill_group']);
		}
	}
	
	if($_POST['action']=='C')
	{
		$staff_detail=get_raw($link, 'select * from staff where staff_id=\''.$_POST['staff_id'].'\'');
		echo '
		     <div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">';
		echo '<form method=post><table  class="table table-striped">';
	    echo '<tr><td><input type=hidden name=staff_id value=\''.$_POST['staff_id'].'\'></td></tr>';
        echo '<tr><td><input type=hidden name=bill_group value=\''.$_POST['bill_group'].'\'></td></tr>';
		echo '<tr><th>Copy salary of</th><td>'.$staff_detail['fullname'].'</td></tr>';
		echo '<tr><th>From Bill</th><td>'.$_POST['bill_group'].'</td></tr>';
		echo '<tr><th>To Bill</th><td>';
		$sql='select bill_group from bill_group order by bill_group desc';
		mk_select_from_sql($link,$sql,'bill_group','to_bill_group','','');
		echo '</td></tr><tr><td  align=center colspan=2>';
		echo '<input type=submit class="btn btn-success" name=action value=select_to_bill>';
		echo '</td></tr></table></form></div></div></div>';				
	}
	if($_POST['action']=='select_to_bill')
	{
		copy_salary($link,$_POST['staff_id'],$_POST['bill_group'],$_POST['to_bill_group']);
		copy_nonsalary($link,$_POST['staff_id'],$_POST['bill_group'],$_POST['to_bill_group']);				
	}		
				
}

if(isset($_POST['bill_group']))
{
	echo '<div class="container" >
		     <div class="row">
		   <div class="col-*-6 mx-auto">';
	list_bill($link,$_POST['bill_group']);
	echo '</div></div></div>';
}
htmltail();

?>

