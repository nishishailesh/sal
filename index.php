<?php
//echo $_SERVER['REMOTE_ADDR'].'<br>';
//echo substr($_SERVER['REMOTE_ADDR'],0,7);
if(substr($_SERVER['REMOTE_ADDR'],0,7)!='11.207.'){echo 'access denied error 444';exit(0);}
session_start();

require_once 'common.php';

echo '<html>';
echo '<head>';

echo '

<style>

table{
   border-collapse: collapse;
}

.border td , .border th{
    border: 1px solid black;
}

.upload{
	background-color:lightpink;
}

.noborder{
 border: none;
}


.hidedisable
{
	display:none;diabled:true
}

</style>


';

echo '</head>';
echo '<body>';
unset($_SESSION['login']);
unset($_SESSION['password']);
if(isset($_GET['message'])){echo "<div class='row'><div class='col-*-6 mx-auto'><div class='text-primary'><h1>".$_GET['message']."</h1></div></div>";}
          
echo'<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">		
	</head>
  <body>
	<div class="container-fluid" style="padding:100px;">
		<div class="row">
			<div class="col-sm-3 bg-light mx-auto">
				<!--<form method=post action=start.php>-->
				<form method=post action=start_salary.php>
					<div class="form-group">
						<h3 class="text-danger text-center  bg-dark">'.$GLOBALS['college'].''.$GLOBALS['city'].'</h3>
					</div>
					<div class="form-group">
					
						<h3 class="text-danger text-center  bg-dark">Salary Management</h3>
					</div>
					<div class="form-group">
						<label for=user>Login ID</label>
						<input  class="form-control" id=user type=text name=login placeholder=Username>
					</div>
					<div class="form-group">						
						<label for=password>Password</label>
						<input  class="form-control" id=password type=password name=password placeholder=Password>
					</div>
					<div class="form-group">						
						<button class="btn btn-primary btn-block" type=submit name=action value=Login>Login</button>
					</div>
				</form>
			</div>
		</div>
	 </div>		
   </div>
  </body>
</html>';
?>
