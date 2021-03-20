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
	$sql='select * from post';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
	      <table  class="table table-striped">';
	echo '<tr><th  colspan="5"style="background-color:lightblue;text-align: center;" ><h4>Add / Modify / Delete Post</h4></th></tr>';
	echo '<tr style="background-color:lightgray;"><th>Shortform</th><th>Post</th><th>Senctioned</th><th>Class</th><th>Action</th></tr>';
		echo '<form method=post ><tr>';
		echo '<td>';
		echo '<input placeholder="Shorform" type=text size=5  name=shortform >';
		echo '</td>';
		echo '<td>';
		echo '<input placeholder="Post" type=text size=30 name=post >';
		echo '</td>';
		echo '<td>';
		echo '<input placeholder="Senctioned" type=text size=7 name=senctioned >';
		echo '</td>';		
		echo '<td>';
		echo '<input placeholder="Class" type=text size=5 name=class >';
		echo '</td>';
		echo '<td>';
		echo '<button class="btn btn-success" title="Save"type=submit name=action value=insert>S</button>';
		echo '</td>';
		echo '</tr></form>';
	while($ra=mysqli_fetch_assoc($result))
	{
		echo '<form method=post ><tr>';
		echo '<td>';
		echo '<input  type=text size=5  name=shortform value=\''.$ra['shortform'].'\'>';
		echo '</td>';
		echo '<td>';
		echo '<input  readonly type=text size=30 name=post  value=\''.$ra['post'].'\'>';
		echo '</td>';
		echo '<td>';
		echo '<input  type=text size=7 name=senctioned  value=\''.$ra['senctioned'].'\'>';
		echo '</td>';		
		echo '<td>';
		echo '<input  type=text size=5 name=class  value=\''.$ra['class'].'\'>';
		echo '</td>';
		echo '<td>';
		echo '<button class="btn btn-success " type=submit title="Save" name=action value=update>S</button>';
		echo '<button class="btn btn-danger " type=submit title="Delete" name=action value=delete onclick="return confirm(\'Data will be deleted permanenty\')" >D</button>';
		echo '</td>';
		echo '</tr></form></div></div></div>';

	}

	echo '</table>';
}

function insert($link)
{
	$sql='insert into post (shortform,post,senctioned,class) 
							values(	\''.$_POST['shortform'].'\',
									\''.$_POST['post'].'\',
									\''.$_POST['senctioned'].'\',
									\''.$_POST['class'].'\')';
	//echo $sql;
	
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}								
}

function update($link)
{
	$sql='update post set
							shortform=\''.$_POST['shortform'].'\',
							senctioned=\''.$_POST['senctioned'].'\',
							class=\''.$_POST['class'].'\'
					where 
							post=\''.$_POST['post'].'\'';
	echo $sql;
	
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}								
}

function del($link)
{
	$sql='delete from post where post=\''.$_POST['post'].'\'';
	
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

