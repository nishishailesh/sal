<?php
session_start();
require_once 'common.php';
require_once 'common_js.php';

//////////////
$link=connect();
head();
menu($link);
echo '  <div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
	      <table class="table table-striped" >
      <form method=post action=edit_salary.php>
      <table class="table table-striped">
      <th colspan=2  style="background-color:lightblue;text-align: center;"><h4>Choose Staff for whose salary is to be edited</h4></th>';
echo	'<tr>';
echo 		'<td>Staff ID:</td><td>';
get_staff_id($link);
echo '</td></tr>';
echo	'<tr>';
echo 		'<td></td><th colspan=2 align=center><input class="btn btn-success"  type=submit name=action value=edit_salary></th>';
echo 	'</tr>';
echo 	'</table></form></div></div></div>';

htmltail();

?>
