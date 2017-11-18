<?php
//uncomment to disable execution
exit(0);
session_start();
$nojunk='defined';
require_once 'common.php';
//require_once('tcpdf/tcpdf.php');
//require_once('Numbers/Words.php');
$link=connect();


$sql='select * from `TABLE 14`';
if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
while($ar=mysqli_fetch_assoc($result))
{
	//print_r($ar);
	foreach($ar as $key=>$value)
	{
		if($key=='staff_id' || $key=='fullname'){$sqll='';}
			
		elseif($key[0]=='^')
		{
			$f=substr($key,1);

			$sql_find_new='select salary_type_id from  salary_type where name=\''.$f.'\'';
			if(!$r_find=mysqli_query($link,$sql_find_new)){echo mysqli_error($link);return FALSE;}
			$idf=mysqli_fetch_assoc($r_find);
			$sqll='insert into salary 
						(staff_id,bill_group,salary_type_id,remark)
								values
						(\''.$ar['staff_id'].'\',
						 \'34170501\',
						 \''.$idf['salary_type_id'].'\',
						 \''.$value.'\')
						 
						 ON DUPLICATE KEY UPDATE    
							remark=\''.$value.'\'
						 ';			
						 
		//echo $sqll;
		}
		elseif($key[0]=='_')
		{
			$f=substr($key,1);			

		$sql_find_new='select nonsalary_type_id from  nonsalary_type where name=\''.$f.'\'';
			if(!$r_find=mysqli_query($link,$sql_find_new)){echo mysqli_error($link);return FALSE;}
			$idf=mysqli_fetch_assoc($r_find);

			$sqll='insert into nonsalary 
						(staff_id,bill_group,nonsalary_type_id,data)
								values
						(\''.$ar['staff_id'].'\',
						 \'34170501\',
						 \''.$idf['nonsalary_type_id'].'\',
						 \''.$value.'\')
						 
						 ON DUPLICATE KEY UPDATE    
							data=\''.$value.'\'
						 ';			
		}
		else
		{
			$f=$key;

			//delete from salary;delete from nonsalary
			//GPF Advance Recovery non IV
			//GPF Advance Recovery non IV
			$sql_find_new='select salary_type_id from  salary_type where name=\''.$f.'\'';
			if(!$r_find=mysqli_query($link,$sql_find_new)){echo mysqli_error($link);return FALSE;}
			$idf=mysqli_fetch_assoc($r_find);
			echo $f.'<br>';
			$sqll='insert into salary 
						(staff_id,bill_group,salary_type_id,amount)
								values
						(\''.$ar['staff_id'].'\',
						 \'34170501\',
						 \''.$idf['salary_type_id'].'\',
						 \''.$value.'\')
						 
						 ON DUPLICATE KEY UPDATE    
							amount=\''.$value.'\'
						 ';			
			
		}
		//echo $sqll;
		if(strlen($sqll)>0)
		{
			if(!$rs=mysqli_query($link,$sqll)){echo mysqli_error($link);return FALSE;}
		}
	}
}


?>

