<?php
session_start();
require_once 'common.php';

$link=connect();
head();
menu($link);

//print_r($_POST);

/*
Bill Group	
Date of Preparation	[Show calendar]
From Date	[Show calendar]
To Date	[Show calendar]
Head	
Bill Type	
Remark
*/

function read_bill_group_to_copy($link)
{
	echo '';
	$sql='select bill_group from bill_group order by bill_group desc';
	echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
	      <table class="table table-striped"><form method=post>';
	echo '<tr><th colspan=2 style="background-color:lightblue;text-align: center;"><h4>Copy old bill to new bill</h4></th></tr>';
	echo '<tr><td>Select Bill to copy</td><td>';
	mk_select_from_sql($link,$sql,'bill_group','from_bill_group','','');

	echo '<tr><td>Write new Bill Group</td><td>';
	echo '<input type=text name=bill_group placeholder="YYMMNNXX">';
	echo '</td></tr>';
			
	echo '</td><tr><td>Date of Preparation</td><td>';
	echo '<input type=text class=datepicker id=date_of_preparation name=date_of_preparation>';
	
	echo '</td><tr><td>Period From:</td><td>';
	echo '<input type=text class=datepicker id=from_date name=from_date>';

	echo '</td><tr><td>Period To:</td><td>';
	echo '<input type=text class=datepicker id=to_date name=to_date>';

	echo '</td><tr><td>Head</td><td>';
	echo '<input type=text name=head >';
	
	echo '</td><tr><td>Bill Type:</td><td>';
	mk_select_from_table($link,'bill_type','','');

	echo '</td><tr><td>Remark:</td><td>';
	echo '<input type=text name=remark >';
	
	echo '</td></tr><tr><td>Locked</td><td>';
	mk_select_from_array(array(0,1),'locked','','');
	echo '</td></tr>';

	echo '</tr>';

	
	echo '<tr><td  align=center colspan=2>';
	echo '<button class="btn btn-success" type=submit name=action value=copy_bill_1 onclick="return confirm(\'Salary will be copied to new bill\')">Copy</button>';
	echo '</td></tr><tr><td colspan=2>All salaries of old bill will be copied to new bill';
	
	echo '</td></tr></table></form><div></div></div>';	
}

/////////////Main script start from here//////////////


if(!isset($_POST['action'])){echo 'no action specified';exit(0);}

if($_POST['action']=='copy_bill')
{
	read_bill_group_to_copy($link);
}
elseif($_POST['action']=='copy_bill_1')
{
	if(add_bill_group($link,$_POST))
	{
		//copy_bill_salary($link,$_POST['from_bill_group'],$_POST['bill_group']);
		copy_bill_nonsalary($link,$_POST['from_bill_group'],$_POST['bill_group']);
		//So salary is not copied
		//So bill listing must be nonsalary based
	}
}
htmltail();	
?>

