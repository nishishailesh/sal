<?php
session_start();
require_once '/var/gmcs_config/staff.conf';
//echo '<pre>';print_r($_POST);echo '</pre>';

function login_varify()
{
	return mysqli_connect('127.0.0.1',$GLOBALS['main_user'],$GLOBALS['main_pass']);
}



/////////////////////////////////
function select_database($link)
{
	return mysqli_select_db($link,'dc');
}


function check_user($link,$u,$p)
{
	$sql='select * from user where id=\''.$u.'\'';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	$result_array=mysqli_fetch_assoc($result);
	if(md5($p)==$result_array['password'])
	{
		return true;
	}
	else
	{
		return false;
	}
}



function logout()
{
	session_start(); //Start the current session
	session_destroy(); //Destroy it! So we are logged out now
	header("location:".$GLOBALS['rootpath']."/common/index.php"); //configure absolute path of this file for access from anywhere
}
///////////////////////////////////
function connect()
{
	if(!$link=login_varify())
	{
		echo 'database login could not be verified<br>';
	
		exit();
	}


	if(!select_database($link))
	{
		echo 'database could not be selected<br>';
	
		exit();
	}
	
	if(!check_user($link,$_SESSION['login'],$_SESSION['password']))
	{
		echo 'application user could not be varified<br>';
		
		exit();
	}
	
return $link;
}



function export_to_csv($sql,$link)
{
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);}
	$fp = fopen('php://output', 'w');
	if ($fp && $result) 
	{
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="export.csv"');
		
		$first='yes';
		
		while ($row = mysqli_fetch_assoc($result))
		{
			if($first=='yes')
			{
				fputcsv($fp, array_keys($row));
				$first='no';
			}
			
			fputcsv($fp, array_values($row));
		}
	}	
	
}



$link=connect();
export_to_csv(base64_decode($_POST['str']),$link);

?>
