<?php
session_start();
require_once 'common.php';

if(!isset($_SESSION['login']))
{
	$_SESSION['login']=$_POST['login'];
}


if(!isset($_SESSION['password']))
{
	$_SESSION['password']=$_POST['password'];
}

//////////////
$link=connect();
menu();
?>
