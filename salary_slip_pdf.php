<?php
session_start();
$nojunk='yes';
set_time_limit(360);
require_once 'common.php';
require_once 'menu_salary.php';
require_once('tcpdf/tcpdf.php');
//require_once('Numbers/Words.php');
//print_r($_POST);
$link=connect();
head();
menu($link);

$GLOBALS['page_size']=2;
		

function mk_select_from_table11($link,$field,$disabled,$default)
{
	$sql='select `'.$field.'` from '.$field;
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
		echo '<select  '.$disabled.' name='.$field.'>';
		$result_array=mysql_num_rows($result);
		while($result_array=mysqli_fetch_assoc($result))
		{
		if($result_array[$field]==$default)
		{
			echo '<option selected  > '.$result_array[$field].' </option>';
			
		}
		else
			{
				echo '<option  > '.$result_array[$field].' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

if(isset($_POST['action']))
{
	if($_POST['action']=='print_salary')
	{
		//echo 'read bill group';
		get_bill_group1($link);
	}

	elseif($_POST['action']=='select_bill_group')
	{
		
		
		$sql='select distinct nonsalary.staff_id,fullname from nonsalary,staff where 
						bill_group=\''.$_POST['bill_group'].'\' 
						and 
						nonsalary.staff_id=staff.staff_id order by fullname';
											
		if(!$result=mysqli_query($link,$sql)){return FALSE;}

		$tot=mysqli_num_rows($result);

	
		//echo $tot;
		//$page=$tot/$limit;
		//$page=ceil($page);
		//$pages=ceil($tot/$record_per_page);
		
		echo '<br>';
		//echo ' total record=', $tot;
		echo '<br><br>';
		if($tot>0)
		{
		for($i=0; $i<$tot; $i=$ppp+$i)
		{
			$ppp=$_POST['pno'];
		   // echo'print par page=',  $ppp, '<br>';
		    
		    $ps=min($ppp,$tot-$i);
		   // echo $ps;
			echo '<div class="container-fluid">
	              <div class="row">				
			     <div class="col-*-6 mx-auto">
			      <form method=post action=salary_slip_pdf2.php target=_blank >
			      <button class="btn btn-success " name=from value=\''.$i.'\'>'.($i+1).'-'.($i+$ps).'</button>
			      <input  type= hidden name=to value=\''.($ppp).'\'>
			      <input  type= hidden name=bill_group value=\''.$_POST['bill_group'].'\'>
			      </form>
			      </div>
			      </div>
			      </div>';
			
	
			
			
		}
	}
	else{
		echo'<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
		     <b> <h3 style="color:red;">No Record To Print</h3></b>
		      </div></div></div>';
	}
   }
}	


htmltail();




?>


