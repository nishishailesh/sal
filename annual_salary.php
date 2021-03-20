
<?php
session_start();
require_once 'common.php';

//print_r($_POST);
$link=connect();
head();
menu($link);

echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">';
list_annual_salary($link,$_POST['staff_id'],$_POST['fyear'],$_POST['fmonth'],$_POST['tyear'],$_POST['tmonth']);
echo '</div></div></div>';

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
			
			echo'<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
			 <table class="table table-striped">
			 <tr><td style="color:blue;padding-left:70px;padding-top:60px"><h2>Annual Salary of</h2></td><td>';
			display_staff($link,$_POST['staff_id']);
			echo '</td></tr></table></div></div></div>';
			
	$sql='select distinct bill_group from salary where staff_id=\''.$staff_id.'\' order by bill_group desc';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}	
	echo ' <div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
			      <table class="table table-striped">';
	echo '<tr><th>Bill Group</th><th>Type</th><th>Remark</th><th>Gross</th><th>Deduction</th><th>Net</th>';
	print_one_h_salary_header($link,'');
	echo '</tr>';
			
	while($bg=mysqli_fetch_assoc($result))
	{ 
		//echo $fyear.'<br>';
		//echo $tyear.'<br>';
		//echo $bg['bill_group'].'<br>';
		//echo substr($bg['bill_group'],2,2).'<br>';
		
	
	$from=$fyear*100+$fmonth;
	$to=$tyear*100+$tmonth;
			


		//if((substr($bg['bill_group'],2,2)>=$fyear) &&
		  // (substr($bg['bill_group'],2,2)<=$tyear) && 
		   //(substr($bg['bill_group'],4,2)>=$fmonth) && 
		   //(substr($bg['bill_group'],4,2)<=$tmonth))
			if(substr($bg['bill_group'],2,4)>=$from && substr($bg['bill_group'],2,4)<=$to)
		  {

		    $ar=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
			echo '<tr>
			<td>'.$ar['bill_group'].'</td>
			<td>'.$ar['bill_type'].'</td>
			<td>'.$ar['remark'].'</td>';
			print_one_h_salary($link,$staff_id,$bg['bill_group'],'');
			echo '</tr>';
		 
		 }
	}
	echo '</table></div></div></div>';

}

htmltail();
?>

