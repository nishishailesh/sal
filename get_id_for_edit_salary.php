<?php
session_start();
require_once 'common.php';
require_once 'common_js.php';

//////////////
$link=connect();
menu();
echo '<form method=post action=edit_salary.php><table style="background-color:lightgreen;" border=1 align=center><th colspan=2>Choose Staff for whose salary is to be edited</th>';
echo	'<tr>';
echo 		'<td>Staff ID:</td><td>';
get_staff_id($link);
echo '</td></tr>';
echo	'<tr>';
echo 		'<th colspan="2"><input type=submit name=action value=edit_salary></th>';
echo 	'</tr>';
echo 	'</table></form>';
?>
