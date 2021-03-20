<?php
session_start();
require_once 'common.php';
require_once 'common_js.php';

//////////////
$link=connect();
head();
menu($link);
echo '<form method=post action=annual_all_salary.php>
         <div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
	      <table class="table table-striped" >
		<th colspan=2 style="background-color:lightblue;text-align: center;"><h4>Choose Staff for whose annual salary is to be viewed</h4></th>';
echo	'<tr>';
echo 		'<td>Staff ID:</td><td>';
get_staff_id($link);
echo '</td></tr>';

echo 		'<td></td><td colspan="2"><input type=submit  class="btn btn-success" name=action value=annual_salary></td>';
echo 	'</tr>';
echo 	'</table></div></div></div></form>';
htmltail();
?>

