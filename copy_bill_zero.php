<?php
session_start();
require_once 'common.php';

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
	echo '<form method=post>';
	$sql='select bill_group from bill_group order by bill_group desc';
	echo '<table class=border align=center style="background-color:lightgreen;">';
	echo '<tr><th colspan=2>Copy old bill to new bill</th></tr>';
	echo '<tr><th>Select Bill to copy</th><td>';
	mk_select_from_sql($link,$sql,'bill_group','from_bill_group','','');

	echo '<tr><th>Write new Bill Group</th><td>';
	echo '<input type=text name=bill_group placeholder="YYMMNNXX">';
	echo '</td></tr>';
			
	echo '</td><tr><th>Date of Preparation</th><td>';
	echo '<input type=text class=datepicker id=date_of_preparation name=date_of_preparation>';
	
	echo '</td><tr><th>Period From:</th><td>';
	echo '<input type=text class=datepicker id=from_date name=from_date>';

	echo '</td><tr><th>Period To:</th><td>';
	echo '<input type=text class=datepicker id=to_date name=to_date>';

	echo '</td><tr><th>Head</th><td>';
	echo '<input type=text name=head >';
	
	echo '</td><tr><th>Bill Type:</th><td>';
	mk_select_from_table($link,'bill_type','','');

	echo '</td><tr><th>Remark:</th><td>';
	echo '<input type=text name=remark >';
	


	echo '</tr>';

	
	echo '<tr><td  align=center colspan=2>';
	echo '<button type=submit name=action value=copy_bill_1 onclick="return confirm(\'Salary will be copied to new bill\')">Copy</button>';
	echo '</td></tr><tr><td colspan=2>All salaries of old bill will be copied to new bill';
	
	echo '</td></tr></table></form>';	
}

/////////////Main script start from here//////////////

$link=connect();

menu();

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
	}
}
	
?>

