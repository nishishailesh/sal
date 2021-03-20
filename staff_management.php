<?php
session_start();
require_once 'common.php';

//print_r($_POST);

/////////////Main script start from here//////////////

$link=connect();
head();
menu($link);
function edit_staff($link)
{
	$sql='select * from staff order by fullname';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo ' <div class="container" >
		     <div class="row">
		     <div class="col-*-10 mx-auto">				
	      <table class="table table-striped ">';
	echo '<tr style="background-color:lightblue;text-align: center;"><th  colspan="4"><h5>Add / Modify / Delete Staff</h5></th></tr>';
	echo '<tr style="background-color:lightpink;text-align: center; "><th colspan="4"><h5>Use Aadhar Card as Staff ID</h5></th></tr>';
	echo '<tr style="background-color:lightgray;"><th>ID</th><th>Name</th><th>Mobile No.</th><th>Action</th></tr>';
		echo '<form method=post ><tr>';
		echo '<td>';
		echo '<input placeholder="New Aadhar" type=text size=15  name=staff_id >';
		echo '</td>';
		echo '<td>';
		echo '<input placeholder="Name" type=text size=30 name=fullname >';
		echo '</td>';
        echo '<td>';
		echo '<input placeholder="Mobile No." type=text size=10 name=mobile >';
		echo '</td>';
		echo '<td>';
		echo '<button class="btn btn-success " type=submit title="Save"  name=action value=insert>S</button>';
		echo '</td>';
		echo '</tr></form>';
	while($ra=mysqli_fetch_assoc($result))
	{
		echo '<form method=post style="margin-bottom:0;"><tr>';
		echo '<td>';
		echo '<input type=text size=15  name=staff_id value=\''.$ra['staff_id'].'\'>';
		echo '<input type=hidden size=15  name=old_staff_id value=\''.$ra['staff_id'].'\'>';
		echo '</td>';
		echo '<td>';
		echo '<input type=text size=30 name=fullname value=\''.$ra['fullname'].'\'>';
		echo '</td>';
        echo '<td>';
		echo '<input type=text size=10 name=mobile value=\''.$ra['mobile'].'\'>';
		echo '</td>';
		echo '<td>';
		echo '<button class="btn btn-success" type=submit title="Save" name=action value=update>S</button>';
		echo '<button  class="btn btn-danger " type=submit  title="Delete" name=action value=delete onclick="return confirm(\'Data will be deleted permanenty\')" >D</button>';
		echo '</td>';
		echo '</tr></form>';
	}

	echo '</table></div></div></div>';
}

function insert($link)
{
	$sql='insert into staff (staff_id,fullname,mobile) 
							values(	\''.$_POST['staff_id'].'\',
									\''.$_POST['fullname'].'\',
									\''.$_POST['mobile'].'\')';
	//echo $sql;
	
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}								
}

function update($link)
{
	$sql='update staff set
							staff_id=\''.$_POST['staff_id'].'\',
							fullname=\''.$_POST['fullname'].'\',
							mobile=\''.$_POST['mobile'].'\'
					where 
							staff_id=\''.$_POST['old_staff_id'].'\'';
	//echo $sql;
	
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}								
}

function del($link)
{
	$sql='delete from staff where staff_id=\''.$_POST['staff_id'].'\'';
	
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}else{echo 'deleted: '.mysqli_affected_rows($link);}

}


if(isset($_POST['action']))
{
		if($_POST['action']=='insert')
		{
			insert($link);
		}
		if($_POST['action']=='update')
		{
			update($link);
		}		
		if($_POST['action']=='delete')
		{
			del($link);
		}		
}	

edit_staff($link);

htmltail();


?>

