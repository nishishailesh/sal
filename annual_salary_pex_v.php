<?php
session_start();
$nojunk='';
require_once 'common.php';

//print_r($_POST);
$link=connect();
//head();
//menu($link);


//list_annual_salary($link,$_POST['staff_id'],$_POST['fyear'],$_POST['fmonth'],$_POST['tyear'],$_POST['tmonth']);

vlist_annual_salary($link,$_POST['staff_id'],$_POST['fyear'],$_POST['fmonth'],$_POST['tyear'],$_POST['tmonth']);

function print_one_h_salary($link,$staff_id,$bill_group,$format_table='')
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
								
		if($ar['type']=='+'){$ptbl=$ptbl.'<td>'.$dt['amount'].'</td>';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'<td>'.$dt['amount'].'</td>';}	
	}
	
	$tbl=$ptbl.$mtbl;
			
	
	
	$pmn=find_sums($link,$staff_id,$bill_group);

	
	
	$summary_column='<th>'.$pmn[0].'</th><th>'.$pmn[1].'</th><th>'.$pmn[2].'</th>';
		
	echo $summary_column.$tbl;
}

function print_one_h_salary_header($link,$format_table='')
{
	if(strlen($format_table)==0){$format_table='salary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$mtbl='';
	
	
	while($ar=mysqli_fetch_assoc($result))
	{				
		if($ar['type']=='+'){$ptbl=$ptbl.'<td>(+)'.$ar['name'].'</td>';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'<td>(-)'.$ar['name'].'</td>';}	
	}
	
	$tbl=$ptbl.$mtbl;
			
	echo $tbl;
}

function list_annual_salary($link,$staff_id,$fyear,$fmonth,$tyear,$tmonth)
{
	
	echo	'<div class="container-fluid" >
				<div class="row">
					<div class="col-*-12 mx-auto">
						<table class="table table-striped">
							<tr>
								<td>';
									display_staff($link,$_POST['staff_id']);
	echo 						'</td>
							</tr>
						</table>
					</div>
				</div>';

	echo	'<div class="row">
					<div class="col-*-12 mx-auto">';
								
	$sql='select distinct bill_group from salary where staff_id=\''.$staff_id.'\' order by bill_group';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}	
	echo 				'<table class="table table-striped" border=1>';
	echo 					'<tr>
									<th>Bill Group</th>
									<th>Type</th>
									<th>Remark</th>
									<th>Gross</th>
									<th>Deduction</th>
									<th>Net</th>';
									print_one_h_salary_header($link,'');
	echo 					'</tr>';
			
	while($bg=mysqli_fetch_assoc($result))
	{ 
		$from=$fyear*100+$fmonth;
		$to=$tyear*100+$tmonth;
		if(substr($bg['bill_group'],2,4)>=$from && substr($bg['bill_group'],2,4)<=$to)
		{
		    $ar=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
			echo 			'<tr>
								<td>'.$ar['bill_group'].'</td>
								<td>'.$ar['bill_type'].'</td>
								<td>'.$ar['remark'].'</td>';
								print_one_h_salary($link,$staff_id,$bg['bill_group'],'');
			echo 			'</tr>';
		 
		 }
	}
	echo 				'</table>
					</div>
				</div>
			</div>';

}


function vlist_annual_salary($link,$staff_id,$fyear,$fmonth,$tyear,$tmonth)
{
	display_staff($link,$staff_id);	
	$format_table='salary_type';
	$sql='select * from `'.$format_table.'` order by type,salary_type_id';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	
	$format_table='nonsalary_type';
	$sqln='select * from `'.$format_table.'` order by nonsalary_type_id';
	if(!$resultn=mysqli_query($link,$sqln)){echo mysqli_error($link);return FALSE;}
	

	$sqll='select distinct bill_group from salary where staff_id=\''.$staff_id.'\' order by bill_group';
	if(!$resultt=mysqli_query($link,$sqll)){echo mysqli_error($link); return FALSE;}
	
	echo '<table border=1 style="border-collapse:collapse;text-align: right;font-family:monospaced;flex-wrap: nowrap"}><tr><td>Bill Group</td>';
	$bill_group=array();
	while($bg=mysqli_fetch_assoc($resultt))
	{ 
		echo '<td>'.$bg['bill_group'].'</td>';
		$bill_group[]=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
	}
	echo '</tr>';

	echo '<tr><td>Remark</td>';
	foreach($bill_group as $value)
	{
		echo '<td>'.$value['remark'].'</td>';
	}
	echo '</tr>';
	
	while($nst=mysqli_fetch_assoc($resultn))
	{	
		echo '<tr><td>'.substr($nst['name'],0,12).'</td>';
		foreach($bill_group as $value)
		{
		$raw=get_raw($link,'select * from nonsalary 
				where 
						bill_group=\''.$value['bill_group'].'\' 
						and staff_id=\''.$staff_id.'\' 
						and nonsalary_type_id=\''.$nst['nonsalary_type_id'].'\'');
		echo '<td>'.substr($raw['data'],0,15).'</td>';
		}
		echo '</tr>';
	}
	

        echo '<tr><td><b>Payment</b></td>';
        foreach($bill_group as $value)
        {
                $pmn=find_sums($link,$staff_id,$value['bill_group']);
                echo '<td>'.$pmn[0].'</td>';
        }
        echo '</tr>';

        echo '<tr><td><b>Deduction<b></td>';
        foreach($bill_group as $value)
        {
                $pmn=find_sums($link,$staff_id,$value['bill_group']);
                echo '<td>'.$pmn[1].'</td>';
        }
        echo '</tr>';

        echo '<tr><td><b>Net</b></td>';
        foreach($bill_group as $value)
        {
                $pmn=find_sums($link,$staff_id,$value['bill_group']);
                echo '<td>'.$pmn[2].'</td>';
        }
        echo '</tr>';


	while($st=mysqli_fetch_assoc($result))
	{	
		echo '<tr><td>('.$st['type'].')'.substr($st['name'],0,12).'</td>';
		foreach($bill_group as $value)
		{
		$raw=get_raw($link,'select * from salary 
				where 
						bill_group=\''.$value['bill_group'].'\' 
						and staff_id=\''.$staff_id.'\' 
						and salary_type_id=\''.$st['salary_type_id'].'\'');
		echo '<td>'.$raw['amount'].'</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
}

htmltail();
?>

