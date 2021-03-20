<?php
session_start();
require_once 'common.php';

//echo '<pre>';print_r($_POST);echo '</pre>';

$link=connect();
head();
menu($link);
     
if(!isset($_POST['bill_group']))
{
	get_bill_group($link);
}
else
{
	if(!isset($_POST['bill_number']) )
	{
		get_bill_number($link,$_POST['bill_group']);
	}
}
htmltail();
?>

