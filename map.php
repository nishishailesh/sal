<?php
#exit(0);
session_start();
$nojunk='defined';
require_once 'common.php';
#require_once('tcpdf/tcpdf.php');
#require_once('Numbers/Words.php');
$link=connect();


$sqll='select * from salaryy';
if(!$resultt=mysqli_query($link,$sqll)){echo mysqli_error($link);return FALSE;}
while($ar=mysqli_fetch_assoc($resultt))
{
	foreach($ar as $old_key=>$old_value)
	{
		$sql_find_new='select * from map where field=\''.$old_key.'\'';
		if(!$r_find=mysqli_query($link,$sql_find_new)){echo mysqli_error($link);return FALSE;}
		if($map=mysqli_fetch_assoc($r_find))
		{
			if($map['type']=='s')
			{
				$sql='insert into salary (staff_id,bill_group,salary_type_id,amount)
					values(\''.$ar['staff_id'].'\',\''.$ar['bill_group'].'\',\''.$map['id'].'\',\''.$old_value.'\')';
			}
			elseif($map['type']=='ns')
			{
				$sql='insert into nonsalary (staff_id,bill_group,nonsalary_type_id,data)
					values(\''.$ar['staff_id'].'\',\''.$ar['bill_group'].'\',\''.$map['id'].'\',\''.$old_value.'\')';
			}
			if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
		}
		
	}
}

?>

