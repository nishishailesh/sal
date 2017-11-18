<?php
session_start();
require_once 'common.php';
require_once 'common_js.php';

//////////////
$link=connect();
menu();
echo '<form method=post action=annual_salary.php>
		<table style="background-color:lightgreen;" border=1 align=center>
		<th colspan=2>Choose Staff for whose annual salary is to be viewed</th>';
echo	'<tr>';
echo 		'<td>Staff ID:</td><td>';
get_staff_id($link);
echo '</td></tr>';
echo '<tr><td>Year(YY)</td><td><input type=number min=1 max=99 name=year></td></tr>';

echo	'<tr>';
echo 		'<th colspan="2"><input type=submit name=action value=annual_salary></th>';
echo 	'</tr>';
echo 	'</table></form>';
?>
