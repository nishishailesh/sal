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

function read_bill_to_edit($link,$bg)
{
	echo '<form method=post>';
	$sql='select * from bill_group  where bill_group=\''.$bg.'\'';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ar=mysqli_fetch_assoc($result);
	echo '<table class=border align=center style="background-color:lightgreen;">';
	echo '<tr><th colspan=2>Edit Bill details</th><td>';
	echo '<tr><th>Bill Group</th><td>';
	echo '<input type=text name=bill_group readonly value=\''.$bg.'\'>';
	echo '</td></tr>';
			
	echo '</td><tr><th>Date of Preparation</th><td>';
	echo '<input type=text value=\''.mysql_to_india_date($ar['date_of_preparation']).'\' class=datepicker id=date_of_preparation name=date_of_preparation>';
	
	echo '</td><tr><th>Period From:</th><td>';
	echo '<input type=text value=\''.mysql_to_india_date($ar['from_date']).'\' class=datepicker id=from_date name=from_date>';

	echo '</td><tr><th>Period To:</th><td>';
	echo '<input type=text value=\''.mysql_to_india_date($ar['to_date']).'\' class=datepicker id=to_date name=to_date>';

	echo '</td><tr><th>Head</th><td>';
	echo '<input value=\''.$ar['head'].'\' type=text name=head >';
	
	echo '</td><tr><th>Bill Type:</th><td>';
	mk_select_from_table($link,'bill_type','',$ar['bill_type']);

	echo '</td><tr><th>Remark:</th><td>';
	echo '<input type=text value=\''.$ar['remark'].'\'  name=remark >';
	


	echo '</tr>';

	
	echo '<tr><td  align=center colspan=2>';
	echo '<button type=submit name=action value=save onclick="return confirm(\'Bill details will be changed\')">Save</button>';	
	echo '</td></tr></table></form>';	
}

function update($link)
{

	$sql='update bill_group set
							date_of_preparation=\''.india_to_mysql_date($_POST['date_of_preparation']).'\',
							from_date=\''.india_to_mysql_date($_POST['from_date']).'\',
							to_date=\''.india_to_mysql_date($_POST['to_date']).'\',
							head=\''.$_POST['head'].'\',
							bill_type=\''.$_POST['bill_type'].'\',
							remark=\''.$_POST['remark'].'\'

					where 
							bill_group=\''.$_POST['bill_group'].'\'';
	//echo $sql;
	
	if(!$result=mysqli_query($link,$sql))
	{echo mysqli_error($link); return FALSE;}
	else {echo 'Changes made to Bill group are Saved<br>Refresh parent page to reflect changes<br>Close this window';}								
}
/////////////Main script start from here//////////////

$link=connect();

menu();

if(!isset($_POST['action'])){echo 'no action specified';exit(0);}

if($_POST['action']=='edit')
{
	read_bill_to_edit($link,$_POST['bill_group']);
}
elseif($_POST['action']=='save')
{
	update($link);
}

	
?>

