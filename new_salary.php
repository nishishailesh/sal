<?php
session_start();
require_once 'common.php';

//print_r($_POST);




/////////////Main script start from here//////////////

$link=connect();
head();
menu($link);

if(!isset($_POST['action'])){echo 'no action specified';exit(0);}

if($_POST['action']=='new_salary_1')
{
	//get_staff_id($link);
	get_bill_group($link);
}
if($_POST['action']=='select_bill_group')
{
	echo '<form method=post>
	      <div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">				
	      <table class="table table-striped ">';
	echo '<tr><td>Add staff to bill <input type=text name=bill_group readonly
					value=\''.$_POST['bill_group'].'\' </td></tr><tr><td>';
	get_staff_id($link);
	echo '<tr><td><input class="btn btn-success" type=submit name=action value=select_staff_id>';	
	echo '</td></tr></table></div></div></div></form>';
}

if($_POST['action']=='select_staff_id')
{
	display_bill($link,$_POST['bill_group']);
	display_staff($link,$_POST['staff_id']);
	//echo '	<span style="background-color:lightgreen" id=basic_data 
	//onclick="showhide(\'nonsal\')">Basic Data</span>';
	edit_nonsalary($link,$_POST['staff_id'],$_POST['bill_group']);
	
	//echo '<br><span style="background-color:lightpink;" id=p_and_d onclick="showhide(\'sal\')">Payment and 	
	//																				Deductions</span>';
	edit_salary($link,$_POST['staff_id'],$_POST['bill_group']);
}


htmltail();
?>

