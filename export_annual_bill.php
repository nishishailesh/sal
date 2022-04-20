<?php
session_start();
$nojunk='';
require_once 'common.php';

$link=connect();

//////////////////Code for salary//////////

export_annual_bill($link,$_POST['bill_group']);
function export_one_h_nonsalary($link,$staff_id,$bill_group,$format_table='')
{
	if(strlen($format_table)==0){$format_table='nonsalary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$tbl='';
	
	
	
	while($ar=mysqli_fetch_assoc($result))
	{
		$dt=get_raw($link,'select * from nonsalary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\' and 
								nonsalary_type_id=\''.$ar['nonsalary_type_id'].'\'');

                if($dt==null)
                {
                        $dt=array('staff_id'=>$staff_id,'bill_group'=>$bill_group,'nonsalary_type_id'=>$ar['nonsalary_type_id'],'data'=>'','remark'=>'');
                }



		$tbl=$tbl.'"'.$dt['data'].'",';
	}
	
	return $tbl;
}
function export_one_h_salary($link,$staff_id,$bill_group,$format_table='')
{
	if(strlen($format_table)==0){$format_table='salary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$mtbl='';
	
	
	while($ar=mysqli_fetch_assoc($result))
	{
		$dt=get_raw($link,'select * from salary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\' and 
								salary_type_id=\''.$ar['salary_type_id'].'\'');

                if($dt==null)
                {
			$dt=array('staff_id'=>$staff_id,'bill_group'=>$bill_group,'salary_type_id'=>$ar['salary_type_id'],'amount'=>0,'remark'=>'');
                }


		if($ar['type']=='+'){$ptbl=$ptbl.'"'.$dt['amount'].'",';}

		elseif($ar['type']=='-'){$mtbl=$mtbl.'"'.$dt['amount'].'",';}	
	}
	
	$tbl=$ptbl.$mtbl;
			
	
	
	$pmn=find_sums($link,$staff_id,$bill_group);

	
	
	$summary_column='"'.$pmn[0].'","'.$pmn[1].'","'.$pmn[2].'",';
		
	return $summary_column.$tbl;
}
function export_one_h_nonsalary_header($link,$format_table='')
{
	if(strlen($format_table)==0){$format_table='nonsalary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$tbl='';
	
	
	while($ar=mysqli_fetch_assoc($result))
	{				
		$tbl=$tbl.'"'.$ar['name'].'",';
	}
	
			
	return $tbl;
}
function export_one_h_salary_header($link,$format_table='')
{
	if(strlen($format_table)==0){$format_table='salary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$mtbl='';
	
	
	while($ar=mysqli_fetch_assoc($result))
	{				
		if($ar['type']=='+'){$ptbl=$ptbl.'"(+)'.$ar['name'].'",';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'"(-)'.$ar['name'].'",';}	
	}
	
	$tbl='"gross","deduction","net",'.$ptbl.$mtbl;
			
	return $tbl;
}

function export_annual_bill($link,$bill_group)
{
	//$staff=get_raw($link,'select * from bill_group where bill_group=\''.$bill_group.'\'');
//	$from=$fyear*100+$fmonth;
	//$to=$tyear*100+$tmonth;			
	$sql='select 
				distinct staff_id
			from 
				salary where bill_group=\''.$bill_group.'\''; 
			//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}	

			$fp = fopen('php://output', 'w');
			if ($fp) 
			{
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="export.csv"');
				
				$bg=get_raw($link,'select * from bill_group where bill_group=\''.$bill_group.'\'');
				//$name=get_raw($link,'select * from staff where staff_id=\''.$staff_id.'\'');
				fputcsv($fp,array_keys($bg));
				fputcsv($fp,$bg);
				//fputcsv($fp,$r);
				$head='Staff Id,Name,'.export_one_h_nonsalary_header($link).export_one_h_salary_header($link).PHP_EOL;
				fputs($fp, $head);

			
				while($staff_list=mysqli_fetch_assoc($result))
				{ 
					//echo floor(($bg['bill_group']%1000000) / 100);
					$ar=get_raw($link,'select * from salary where bill_group=\''.$bill_group.'\' and
					                                                 staff_id=\''.$staff_list['staff_id'].'\'');
					        $name=get_raw($link,'select * from staff where staff_id=\''.$staff_list['staff_id'].'\'');                                        
							$row=$staff_list['staff_id'].",".$name['fullname'].",";
							//print_r($name);
							$row=$row.export_one_h_nonsalary($link,$staff_list['staff_id'],$bill_group).export_one_h_salary($link,$staff_list['staff_id'],$bill_group).PHP_EOL;
							fputs($fp, $row);
							
							//print_r($staff_list);
				}
			}
}

?>

