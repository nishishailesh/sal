<?php
session_start();
$nojunk='';
require_once 'common.php';

$link=connect();

//////////////////Code for salary//////////

export_annual_salary($link,$_POST['staff_id'],$_POST['fyear'],$_POST['fmonth'],$_POST['tyear'],$_POST['tmonth']);

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

		$amt=(isset($dt['amount']))?$dt['amount']:0;
		//if($ar['type']=='+'){$ptbl=$ptbl.'"'.$dt['amount'].'",';}
		if($ar['type']=='+'){$ptbl=$ptbl.'"'.$amt.'",';}

		//elseif($ar['type']=='-'){$mtbl=$mtbl.'"'.$dt['amount'].'",';}
		elseif($ar['type']=='-'){$mtbl=$mtbl.'"'.$amt.'",';}
	}

	$tbl=$ptbl.$mtbl;


	$pmn=find_sums($link,$staff_id,$bill_group);


	
	$summary_column='"'.$pmn[0].'","'.$pmn[1].'","'.$pmn[2].'",';
		
	return $summary_column.$tbl;
}



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

function export_annual_salary($link,$staff_id,$fyear,$fmonth,$tyear,$tmonth)
{
	$staff=get_raw($link,'select * from staff where staff_id=\''.$staff_id.'\'');
	$from=$fyear*100+$fmonth;
	$to=$tyear*100+$tmonth;			
	$sql='select 
				distinct bill_group 
			from 
				salary 
			where 
				staff_id=\''.$staff_id.'\' 
				and
				(mod(bill_group,1000000)) DIV 100 between \''.$from.'\' and \''.$to.'\'
			order by 
				bill_group';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}	

			$fp = fopen('php://output', 'w');
			if ($fp) 
			{
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="export.csv"');
				
				fputcsv($fp,array("ID","NAME"));
				fputcsv($fp,$staff);
				$head='Billgroup,billtype,remark,'.export_one_h_nonsalary_header($link).export_one_h_salary_header($link).PHP_EOL;
				
				fputs($fp, $head);

			
				while($bg=mysqli_fetch_assoc($result))
				{
					//echo floor(($bg['bill_group']%1000000) / 100);
					$ar=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
					if($ar==null){continue;}
							$row='"'.$ar['bill_group'].'","'.$ar['bill_type'].'","'.$ar['remark'].'",';
							$row=$row.export_one_h_nonsalary($link,$staff_id,$bg['bill_group']);
							$row=$row.export_one_h_salary($link,$staff_id,$bg['bill_group']).PHP_EOL;
							fputs($fp, $row);
				}
			}
}

?>

