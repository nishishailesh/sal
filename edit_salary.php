<?php
session_start();
require_once 'common.php';

//print_r($_POST);

$link=connect();
head();
menu($link);

if(isset($_POST['bill_group']) && isset($_POST['staff_id']))
{
	if(isset($_POST['action']))
	{
		if($_POST['action']=='E')
		{
			echo '<div class="container"  style="width:55% !important;">';
			echo '<table  class="table table-striped" ><tr><td style="color:blue;padding-left:70px;padding-top:50px"><h2>Salary Slip of</h2></td><td>';
			display_staff($link,$_POST['staff_id']);
			echo '</td></tr></table></div>';
			display_bill($link,$_POST['bill_group']);
			edit_nonsalary($link,$_POST['staff_id'],$_POST['bill_group']);
			edit_salary($link,$_POST['staff_id'],$_POST['bill_group']);
			echo '';
		}
		
		if($_POST['action']=='C')
		{
			$staff_detail=get_raw($link, 'select * from staff where staff_id=\''.$_POST['staff_id'].'\'');
			echo '<form method=post>';
			echo '<div class="container" border="1" style="width:42% !important;">
			      <table align=center class="table table-striped">';
			echo '<input type=hidden name=staff_id value=\''.$_POST['staff_id'].'\'>';
			echo '<input type=hidden name=bill_group value=\''.$_POST['bill_group'].'\'>';
			echo '<tr><th>Copy salary of</th><td>'.$staff_detail['fullname'].'</td></tr>';
			echo '<tr><th>From Bill</th><td>'.$_POST['bill_group'].'</td></tr>';
			echo '<tr><th>To Bill</th><td>';
			$sql='select bill_group from bill_group order by bill_group desc';
			mk_select_from_sql($link,$sql,'bill_group','to_bill_group','','');
			echo '</td></tr><tr><td  align=center colspan=2>';
			echo '<input class="btn btn-success" type=submit name=action value=select_bill_group>';
			echo '</td></tr></table></div></form>';				
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
			$locked=is_bill_group_locked($link,$_POST['bill_group']);
			if($locked!=0){echo $_POST['bill_group'].' Bill Group locked';}
			else
			{			
				delete_raw_by_id_dpc($link,'salary','staff_id',$_POST['staff_id'],'bill_group',$_POST['bill_group']);
				delete_raw_by_id_dpc($link,'nonsalary','staff_id',$_POST['staff_id'],'bill_group',$_POST['bill_group']);
			}
		}		
		
	}
	
}

echo '<div>';
list_all_salary($link,$_POST['staff_id']);
echo '</div>';

htmltail();
?>

