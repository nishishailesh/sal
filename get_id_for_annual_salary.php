<?php
session_start();
require_once 'common.php';
require_once 'common_js.php';




//////////////
$link=connect();
head();
menu($link);
echo '<form method=post action=annual_salary.php>
         <div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
	      <table class="table table-striped" border="1">
		<th colspan=2 style="background-color:lightblue;text-align: center;"><h4>Choose Staff for whose annual salary is to be viewed</h4></th>';
echo	'<tr>';
echo 		'<td>Staff ID:</td><td>';
get_staff_id($link);
echo '</td></tr>';
echo'<tr><td>From(YY)(MM)</td><td><input type=number min=1 max=99 placeholder="YY" name=fyear value=\''.(date('y')-1).'\'> 	&nbsp;<input type=number min=1 placeholder="MM" max=99 value=3 name=fmonth></td></tr>';
echo'<tr><td>To(YY)(MM)</td><td><input type=number min=1 max=99 placeholder="YY" name=tyear value=\''.date('y').'\'> 	&nbsp;<input type=number min=1 placeholder="MM" max=99 value=2 name=tmonth></td></tr>';
echo	'<tr>';
echo 		'<td></td><td colspan="2"><input type=submit  class="btn btn-success" name=action value=annual_salary></td>';
echo 	'</tr>';
echo 	'</table></div></div></div></form>';
htmltail();
?>
