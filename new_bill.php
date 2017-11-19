<?php
session_start();
require_once 'common.php';
require_once 'common_js.php';

//print_r($_POST);
//////////////
$link=connect();
menu();

if(!isset($_POST['action'])){echo 'no action specified';exit(0);}

if($_POST['action']=='new_bill_1')
{
	read_bill_group($link);
}
if($_POST['action']=='new_bill_2')
{
	read_staff();
}
if($_POST['action']=='new_bill_3')
{
	read_salary();
}

if($_POST['action']=='save_bill_1')
{
	if(add_bill_group($link,$_POST))
	{
		read_staff();
	}
}
if($_POST['action']=='save_bill_2')
{
	add_staff();
}
if($_POST['action']=='save_bill_3')
{
	add_salary();
}


function read_bill_group($link)
{
		/*
	bill_group 	bigint(11)	NO 	PRI 	NULL	
	date_of_preparation 	date	NO 		NULL	
	from_date 	date	NO 		NULL	
	to_date 	date	NO 		NULL	
	head 	varchar(100)	NO 		NULL	
	type 	varchar(100)	NO 		NULL	
	remark 	varchar(100)	NO 		NULL	
		 */

	echo '<table class=border align=center style="background-color:lightgreen;"><form method=post>';
	echo '<tr><th colspan="2">Create new Bill Group</th></tr>';
	echo '<tr><td>Bill Group</td><td><input type=text name=bill_group></td></tr>';
	echo '<tr><td>Date of Preparation</td><td><input type=text readonly class=datepicker id=date_of_preparation name=date_of_preparation></td></tr>';
	echo '<tr><td>From Date</td><td><input type=text readonly class=datepicker id=from_date name=from_date></td></tr>';
	echo '<tr><td>To Date</td><td><input type=text readonly class=datepicker id=to_date name=to_date></td></tr>';
	echo '<tr><td>Head</td><td><input type=text name=head></td></tr><tr><td>Bill Type</td><td>';
	mk_select_from_table($link,'bill_type','','');	
	echo '</td></tr><tr><td>Remark</td><td><input type=text name=remark></td></tr>';
	
	echo '</td></tr><tr><td>Locked</td><td>';
	mk_select_from_array(array(0,1),'locked','','');
	echo '</td></tr>';
	
	echo '<tr><td></td><td><button type=submit name=action value=save_bill_1>Save</button></td></tr>';
	echo '</form></table>';	
}


function read_staff()
{
	
	
}

?>
