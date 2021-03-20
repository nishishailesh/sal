<?php
session_start();
require_once 'common.php';
echo '
<style>
	
table{
   border-collapse: collapse;
}

.border td , .border th{
    border: 1px solid gray;
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

.section_header
{
	background-color:gray;
}
</style>';		



//////////////
$link=connect();
head();
menu($link);
echo '<form method=post action=new_salary.php><table border=1><th colspan=2>Choose Staff for which salary is to be prepared</th>';
echo	'<tr>';
echo 		'<td>Staff ID:</td><td>';
get_staff_id($link);
echo '</td></tr>';
echo '<tr><td>Bill group</td><td><input type=text name=bill_group></td></tr>';
echo '<tr><td>Bill number</td><td><input type=text name=bill_number></td></tr>';
echo	'<tr>';
echo 		'<th colspan="2"><input type=submit name=action value=new_salary></th>';
echo 	'</tr>';
echo 	'</table></form>';
htmltail();
?>
