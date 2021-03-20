<?php
session_start();

require_once 'common.php';

//echo '<br>Sending POST from server<br><pre>';
//print_r($_POST);
//echo '<br>With proper POSTing of data by to-script and proper output by from-script AJAX is complate';
//javascript to encode url and PHP to decode POST value is must
//make a response div in from-script which hide spn
//	echo '<div id=response onclick="showhide(\'spn\');"></div>';

$link=connect();

//date india vs mysql. Corusponding change in edit_dc.php


$locked=is_bill_group_locked($link,$_POST['bill_group']);

if($locked!=0)
{
	echo '<table class=border align="center" style="display:block;background:lightpink;"><tr>';
	echo '<td><h3 style="color:blue;">This Bill is locked. No data changed. Refresh browser to bring back old data</h3></td>';
	echo '</tr></table>';
	exit(0);
}


if($_POST['field']=='from_date' ||$_POST['field']=='to_date' )
{
	$_POST['value']=india_to_mysql_date($_POST['value']);
}


//Array ( [field] => ss_3 [value] => 89 [staff_id] => 2 [bill_group] => 34170505 ) 

$tf=substr($_POST['field'],0,2);
$k=substr($_POST['field'],3);
if($tf=='ss')
{
	if(strlen($_POST['value'])=='0')
	{$value=0;}
	else{$value=$_POST['value'];}
		$sql='insert into salary (staff_id,bill_group,salary_type_id,amount)
					values(
							\''.$_POST['staff_id'].'\',
							\''.$_POST['bill_group'].'\',
							\''.$k.'\',
							\''.$value.'\')
						ON DUPLICATE KEY UPDATE    
							amount=\''.$value.'\'';
		//echo $sql;
}
elseif($tf=='sr')
{

         if(strlen($_POST['value'])=='0')
          {$value=0;}
          else{$value=$_POST['value'];}


	$sql='insert into salary (staff_id,bill_group,salary_type_id,remark)
					values(
							\''.$_POST['staff_id'].'\',
							\''.$_POST['bill_group'].'\',
							\''.$k.'\',
							\''.$value.'\')
						ON DUPLICATE KEY UPDATE 
							remark=\''.$value.'\'';
}
elseif($tf=='ns')
{
	$sql='insert into nonsalary (staff_id,bill_group,nonsalary_type_id,data)
					values(
							\''.$_POST['staff_id'].'\',
							\''.$_POST['bill_group'].'\',
							\''.$k.'\',
							\''.$_POST['value'].'\')
						ON DUPLICATE KEY UPDATE 
							data=\''.$_POST['value'].'\'';
}


if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
$updated=mysqli_affected_rows($link);
$pmn=find_sums($link,$_POST['staff_id'],$_POST['bill_group']);

	echo '<table class=border align="center" style="display:block;background:lightpink;"><tr>';
	echo '<th>Gross</th><th>Deductions</th><th>Net</th></tr><tr>';
	echo '<th>'.$pmn[0].'</th><th>'.$pmn[1].'</th><th>'.$pmn[2].'</th>';
	echo '</tr></table>';

if($updated>0)
{
	echo '<div style="background:lightblue;color:red;display=block;" id=spn  onclick="hide(\'spn\')">
				<span>[X]</span><span style="color:red;">'.$updated.'</span><span>:Last Saved:
				'.$_POST['staff_id'].'=>'.$_POST['bill_group'].'=>'.$_POST['field'].'=>'.$_POST['value'].'</span>
			</div>';
}
else
{
	echo '<div style="background:blue;color:red;display=block;" id=spn  onclick="hide(\'spn\')">
			<span style="background:lightblue;color:red;">[X]</span><span>'.$updated.':Nothing saved</span>
		</div>';
}



?>
