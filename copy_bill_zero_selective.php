
<!--Script by hscripts.com-->
<!-- Free javascripts @ https://www.hscripts.com -->
<script type="text/javascript">
checked=false;
function checkedAll (frm1) {var aa= document.getElementById('frm1'); if (checked == false)
{
checked = true
}
else
{
checked = false
}for (var i =0; i < aa.elements.length; i++){ aa.elements[i].checked = checked;}
}
</script>
<!-- Script by hscripts.com -->


<?php
session_start();
require_once 'common.php';

$link=connect();
head();
menu($link);

//print_r($_POST);

/*
Bill Group	
Date of Preparation	[Show calendar]
From Date	[Show calendar]
To Date	[Show calendar]
Head	
Bill Type	
Remark
*/

function mk_cb_from_sql($link,$sql,$field_name,$display_name)
{

	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	   
		while($result_array=mysqli_fetch_assoc($result))
		{			
			echo '<tr><td>'.$result_array[$display_name].'</td><td><input type=checkbox name=cb_'.$result_array[$field_name].'></td></tr>';
		}
		
		return TRUE;
}
function mk_cb_from_sql_1($link,$sql,$field_name,$display_name)
{
    $ptbl='';
	$mtbl='';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	while($result_array=mysqli_fetch_assoc($result))
		{
								
		if($result_array['type']=='+'){$ptbl=$ptbl.'<tr>
										<td>'.substr($result_array[$display_name],0,30).'</td>
										<td><input type=checkbox name=cb_'.$result_array[$field_name].'></td>';}
										
		elseif($result_array['type']=='-'){$mtbl=$mtbl.'<tr>
										<td>'.substr($result_array[$display_name],0,30).'</td>
										<td><input type=checkbox name=cb_'.$result_array[$field_name].'></td>';}	
	  }
	
   $tbl='	
				<tr align="center">
					<th><h4>Payment</h4></th><th><h4>Deductions</h4></th></tr>
					<tr><td><table class="table table-striped">'.$ptbl.'</table></td><td><table class="table table-striped">'.$mtbl.'</table></td></tr>
				 
		  ';
		echo $tbl;

		return TRUE;			
			//echo '<tr><td>'.$result_array[$display_name].'</td><td><input type=checkbox name=cb_'.$result_array[$field_name].'></td></tr>';
		    //return TRUE;
}
function read_bill_group_to_copy($link)
{
	echo '';
	$sql='select bill_group from bill_group order by bill_group desc';
	echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-3 mx-auto">
	      <table class="table table-striped"><form method=post  id ="frm1">';
	echo '<tr><th colspan=2 style="background-color:lightblue;text-align: center;"><h4>Copy old bill to new bill</h4></th></tr>';
	echo '<tr><td>Select Bill to copy</td><td>';
	mk_select_from_sql($link,$sql,'bill_group','from_bill_group','','');

	echo '<tr><td>Write new Bill Group</td><td>';
	echo '<input type=text name=bill_group placeholder="YYMMNNXX">';
	echo '</td></tr>';
			
	echo '</td><tr><td>Date of Preparation</td><td>';
	echo '<input type=text class=datepicker id=date_of_preparation name=date_of_preparation>';
	
	echo '</td><tr><td>Period From:</td><td>';
	echo '<input type=text class=datepicker id=from_date name=from_date>';

	echo '</td><tr><td>Period To:</td><td>';
	echo '<input type=text class=datepicker id=to_date name=to_date>';

	echo '</td><tr><td>Head</td><td>';
	echo '<input type=text name=head >';
	
	echo '</td><tr><td>Bill Type:</td><td>';
	mk_select_from_table($link,'bill_type','','');

	echo '</td><tr><td>Remark:</td><td>';
	echo '<input type=text name=remark >';
	
	echo '</td></tr><tr><td>Locked</td><td>';
	mk_select_from_array(array(0,1),'locked','','');
	echo '</td></tr>';
     
    echo'<tr align=center><td colspan=2  class="text text-primary"><h5>Tick Mark Fields in Which Amount should be Zero</h5></td></tr>';
	mk_cb_from_sql_1($link,'select * from salary_type','salary_type_id','name');
	
	
    echo '<tr><td  align=center colspan=2>';
	echo '<button class="btn btn-success" type=submit name=action value=copy_bill_1 onclick="return confirm(\'Salary will be copied to new bill\')">Copy</button>';
	echo '</td></tr><tr class="text text-primary"><td colspan=2 >All salaries of old bill will be copied to new bill';

	echo '</td></tr></form></table><div></div></div>';	
}

/////////////Main script start from here//////////////


if(!isset($_POST['action'])){echo 'no action specified';exit(0);}

if($_POST['action']=='copy_bill')
{
	read_bill_group_to_copy($link);
}
elseif($_POST['action']=='copy_bill_1')
{
   /*if(!empty($_POST['check_list']))
     {
    
        foreach($_POST['check_list'] as $selected)
        {
          // echo $selected."</br>";
          $sql='SELECT * FROM `salary_type` WHERE shortform=\''.$selected.'\'';
         if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
       while($ar=mysqli_fetch_assoc($result))
	    {
		//$result=$ar['salary_type_id'];
		//echo $result;
		echo $ar['salary_type_id'] ,'<br>';
	     }
        }*/
     
  if(add_bill_group($link,$_POST))
	 {
	   $selected=array();
		foreach($_POST as $key=>$value)
		{
			if(substr($key,0,3)=='cb_')
			{
				$selected[]=substr($key,3);
			}
		}
		//print_r($selected);
		
		copy_bill_selective($link,$_POST['from_bill_group'],$_POST['bill_group'],$selected);
		copy_bill_nonsalary($link,$_POST['from_bill_group'],$_POST['bill_group']);
		//So salary is not copied
		//So bill listing must be nonsalary based
		
	}

		//print_r($selected);
	
	
}
function copy_bill_selective($link,$b,$tb,$res)
{
	//print_r($res);
 $locked=is_bill_group_locked($link,$tb);
 if($locked!=0){echo $tb.'Bill Group locked'; return;}

   
	$sql='select * from salary where bill_group=\''.$b.'\'';
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	while($ar=mysqli_fetch_assoc($result))
	{
		//print_r ($ar);
		if($ar['amount']>0)
		{
			if(in_array($ar['salary_type_id'],$res))
			{
			   $sqls='insert into salary (staff_id,bill_group,salary_type_id,amount) 
						values(\''.$ar['staff_id'].'\',\''.$tb.'\',\''.$ar['salary_type_id'].'\',\''.$ar['amount'].'\') 
						ON DUPLICATE KEY UPDATE   amount=\'0\'';
			   //echo '<br>'.$sqls;
			   if(!$rs=mysqli_query($link,$sqls)){echo mysqli_error($link);return FALSE;}
			   
	    	}
	    	else
	    	{
			$sqls='insert into salary (staff_id,bill_group,salary_type_id,amount) 
						values(\''.$ar['staff_id'].'\',\''.$tb.'\',\''.$ar['salary_type_id'].'\',\''.$ar['amount'].'\') 
						ON DUPLICATE KEY UPDATE    
						amount=\''.$ar['amount'].'\'';		
										
	       //if(!$rs=mysqli_query($link,$sqls)){echo mysqli_error($link);return FALSE;}
			//echo '<br>==='.$sqls;
		    }
		    if(!$rs=mysqli_query($link,$sqls)){echo mysqli_error($link);return FALSE;}
		}
	}
}
htmltail();	
?>

