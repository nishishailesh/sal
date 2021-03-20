<?php
session_start();
require_once 'common.php';

/////////////Start  of Script///////////////
//print_r($_POST);
$link=connect();
head();
menu($link);
function read_password1()
{
	echo ' <div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
		     <form method=post>
	      <table class="table table-striped ">';
	echo '<tr><th colspan=2 style="background-color:lightblue;text-align: center;"><h4>Change Password</h4></th></tr>';
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
					echo'<div class="row"><div class="col-*-6 mx-auto"><div class="text-primary"><h1>Password changed successfully. Re login!!</h1></div></div>';
					//logout("message=Password changed successfully. Re login!!");
				}
			}
			else
			{
				echo'<div class="row"><div class="col-*-6 mx-auto"><div class="text-primary"><h1>Change password failed!!... old password was wrong</h1></div></div>';
				//logout("message=Change password failed!!... old password was wrong");
			}
		}
		else
		{
			echo '<div class="row"><div class="col-*-6 mx-auto"><div class="text-primary"><h1>New passwords supplied do not match</h1></div></div>';
			//logout("message=Change password failed!!... New Password mismatch");
		}
	}
	else
		{
			echo'<div class="row"><div class="col-*-6 mx-auto"><div class="text-primary"><h1>Change password failed!! <br>Ensure mix of lower case, upper case, number and special characters</h1></div></div>';
			//logout("message=Change password failed!! <br>Ensure mix of lower case, upper case, number and special characters");
		}
 }
}
else
{
	read_password1();	
}


echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
            <table class="table table-bordered">
			<tr><th colspan=3 style="background-color:gray;color:white;text-align: center;margin :0 !important;padding :0 !important;"><h5>Password Hints</h5></th></tr>
			<tr><td colspan=3 >8 or more character, One capital, one number, 1 special character is must</td></tr>
			<tr><td>iamgood</td><td>Unacceptable</td><td>No capital, no number, no special character, less than 8</td></tr>
			<tr><td>Iamgood007</td><td>Unacceptable</td><td>no special character</td></tr>
			<tr><td>Iamgood007$</td><td>Acceptable</td><td>special characters-> ! @ # $ % ^ & * ( ) _ - += { [ } ] | \ / < , > . ; : " \'</td></tr>
            </table>
            </div>
            </div>
            </div>
            
';



echo'</body>';
/*
function update_password($link,$user,$new_password)
{
	$eDate = date('Y-m-d');
    $eDate = date('Y-m-d', strtotime("+1 months", strtotime($eDate)));
    // echo $eDate;	
	$sql='update user set epassword=\''.password_hash($new_password,PASSWORD_BCRYPT).'\',expirydate=\''.$eDate.'\' where id=\''.$user.'\' ';
	
       // $sql='update user set password=MD5(\''.$new_password.'\')and epassword= where id=\''.$user.'\' ';
        //echo $sql;
        if(!$result=mysqli_query($link,$sql))
        {
                echo mysqli_error($link);
                return FALSE;
        }

        if(mysqli_affected_rows($link)==1)
        {
                echo '<h3>Update successful. Close browser and restart it again</h3>';
		return true;
        }
		elseif(mysqli_affected_rows($link)==0)
		{
					echo '<h3>Old and new Passwords same. Nothing is changed.</h3>';
					return true;
		}
}
*/
htmltail();

/*
echo '<pre>';
print_r($_POST);
echo '</pre>';
*/
?>
