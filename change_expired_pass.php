<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<?php
session_start();
require_once 'common.php';

/////////////Start  of Script///////////////
//print_r($_POST);
//$link=connect();
//menu();

	
function read_password1()
{
	echo ' <div class="container-fluid" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
		     <form method=post>
	      <table class="table table-striped ">';
	echo '<tr><th colspan=2 style="background-color:lightblue;text-align: center;"><h4>Change Password for access to Staff Database</h4></th></tr>';
	echo '<tr><td style="margin :0 !important;padding :0 !important;">Login ID</td>	<td style="margin :0 !important;padding :0 !important;"><input readonly=yes type=text name=id value=\''.$_SESSION['login'].'\'></td></tr>'; 
	echo '<tr><td  style="margin :0 !important;padding :0 !important;">Old Password</td>	<td  style="margin :0 !important;padding :0 !important;"><input type=password name=old_password></td></tr>';
	echo '<tr><td  style="margin :0 !important;padding :0 !important;">New Password</td>	<td  style="margin :0 !important;padding :0 !important;"><input type=password name=password_1></td></tr>';
	echo '<tr><td  style="margin :0 !important;padding :0 !important;">Repeat New Password</td>	<td  style="margin :0 !important;padding :0 !important;"><input type=password name=password_2></td></tr>';
	echo '<tr><td colspan=2 align=center  style="margin :0 !important;padding :0 !important;"><button class="btn btn-success" type=submit name=action value=change_password>Change Password</button></td></tr>';
	echo '</table></form></div></div></div>';
}

if(isset($_POST['action']))
{

	if($_POST['action']=='change_password')
	{
		if(!$link=login_varify())
		{
			//echo 'database login could not be verified<br>';
			logout("message=database not connected");	
			exit();
		}


		if(!select_database($link))
		{
			//echo 'database could not be selected<br>';
			logout("message=database not connected");	
			exit();
		}
			
		if(is_valid_password($_POST['password_1'])==$_POST['password_1'])
		{
			if($_POST['password_1']==$_POST['password_2'])
			{
				//echo 'OK.  New passwords matches';
				if(check_old_password($link,$_POST['id'],$_POST['old_password']))
				{
					if(!update_password($link,$_POST['id'],$_POST['password_1'])){echo 'Password update failed!';}
					else
					{
						//echo'Password changed successfully. Re login!!';
						logout("message=Password changed successfully. Re login!!");
					}
				}
				else
				{
					//echo'Change password failed!!<br> old password was wrong';
					logout("message=Change password failed!!<br> old password was wrong");
				}
			}
			else
			{
				//echo'Change password failed!!<br> New Password mismatch';
				logout("message=Change password failed!!<br> New Password mismatch");
			}
		}
		else
		{
			//echo'Change password failed!! <br>Ensure mix of lower case, upper case, number and special characters';
			logout("message=Change password failed!! <br>Ensure mix of lower case, upper case, number and special characters");
		}
	}
	
	if($_POST['action']=='change_password_step_1')
	{
		//echo'hello';
		read_password1();
	}
 }
echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
            <table class="table table-bordered">
			<tr><th colspan=3 style="background-color:gray;color:white;text-align: center;margin :0 !important;padding :0 !important;"><h5>Password Hints</h5></th></tr>
			<tr><td>iamgood</td><td>Unacceptable</td><td>No capital, no number, no special character, less than 8</td></tr>
			<tr><td>Iamgood007</td><td>Unacceptable</td><td>no special character</td></tr>
			<tr><td>Iamgood007$</td><td>Acceptable</td><td>special characters-> ! @ # $ % ^ & * ( ) _ - += { [ } ] | \ / < , > . ; : " \'</td></tr>
            </table>
            </div>
            </div>
            </div>
 <div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
	  <table class="table table-bordered">
		<tr ><th style="background-color:gray;color:white;text-align: center;margin :0 !important;padding :0 !important;"><h5><b>Help</b></h5></th></tr>
		<tr><td><li>Write old and new password carefully.</td></tr>
		<tr><td><li>close browser and start again after changing password</td></tr>
		<tr><td><li>Change password frequently</td></tr>
		<tr><td><li>Donot reveal your password to anybody.</td></tr>
		<tr><td><li>If your colleague needs access, ask them to contact HOD</td></tr>
		<tr><td><li>If you can not access your account, meet IT section of the college/hospital</td></tr>
 </table></div></div></div></html></div></div></div>';



echo'</body>';
