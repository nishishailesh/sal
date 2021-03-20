<?php
session_start();
require_once 'common.php';

//print_r($_POST);

$link=connect();

$GLOBALS['basic_e_id']=3;		//est
$GLOBALS['gp_e_id']=4;			//est
$GLOBALS['basic_o_id']=1;			//12
$GLOBALS['gp_o_id']=2;			//12
$GLOBALS['da_id']=7;
//following update required
$GLOBALS['da_id']=7;
$GLOBALS['npa_id']=5;
$GLOBALS['hra_id']=9;
$GLOBALS['ceiling_extra_id']=19;

$GLOBALS['qtr_id']=9;
$GLOBALS['real_off_id']=13;
$GLOBALS['real_est_id']=14;

head();
menu($link);

//repacement to save_salary.php AJAX
function ui_sal($link,$s,$b,$sti,$val)
{

$locked=is_bill_group_locked($link,$b);
if($locked!=0){echo $b.' Bill Group locked!!'; return;  }
	
		$sql='insert into salary (staff_id,bill_group,salary_type_id,amount)
					values(
							\''.$s.'\',
							\''.$b.'\',
							\''.$sti.'\',
							\''.$val.'\')
						ON DUPLICATE KEY UPDATE
							amount=\''.$val.'\'';
		if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
		return $updated=mysqli_affected_rows($link);
}

function recalculate($link,$s,$b)
{
	$basic_off=get_sfval($link,$b,$s,$GLOBALS['basic_o_id']);
	$basic_est=get_sfval($link,$b,$s,$GLOBALS['basic_e_id']);

	$gp_off=get_sfval($link,$b,$s,$GLOBALS['gp_o_id']);
	$gp_est=get_sfval($link,$b,$s,$GLOBALS['gp_e_id']);
	
	$bg_off=$basic_off['amount']+$gp_off['amount'];
	$bg_est=$basic_est['amount']+$gp_est['amount'];
	
		
	$qtr=get_nsfval($link,$b,$s,$GLOBALS['qtr_id']);
	$gqtr=strlen($qtr['data']);

	$max_bg_off=round(($_POST['ceil']*4/5));
	$max_bg_est=$_POST['ceil']; //because no NPA
	
	$off_for_cal=min($bg_off,$max_bg_off);
	$est_for_cal=min($bg_est,$max_bg_est);
		
	$npa=round(	$off_for_cal*$_POST['npa']	);
	
	//$ceil=	max($bg_off-$max_bg_off,0)
	//			+
	//		max($bg_est-$max_bg_est,0);
	
	$da=round(($off_for_cal+$npa)*$_POST['da']
			+
				$est_for_cal*$_POST['da'])	
			;
			
	
	$hra=round(($off_for_cal+$npa)*$_POST['hra']
			+
				$est_for_cal*$_POST['hra']	)
			;
			
	//echo '<h3>NPA:'.$npa.'</h3>';
	//echo '<h3>HRA:'.$hra.'</h3>';
	//echo '<h3>DA:'.$da.'</h3>';
	//echo '<h3>CEIL:'.$ceil.'</h3>';
	ui_sal($link,$s,$b,$GLOBALS['npa_id'],$npa);
	if($gqtr==0)
	{
		ui_sal($link,$s,$b,$GLOBALS['hra_id'],$hra);
	}
	else
	{
		ui_sal($link,$s,$b,$GLOBALS['hra_id'],0);
	}
	ui_sal($link,$s,$b,$GLOBALS['da_id'],$da);
	//ui_sal($link,$s,$b,$GLOBALS['ceiling_extra_id'],$ceil);
}

function display_calculate($link,$s,$b)
{
	echo '<form method=post><button name=action value=calculate ><h3>Calculate</h3></button>';
	echo '<input type=hidden name=staff_id value=\''.$s.'\'>';
	echo '<input type=hidden name=bill_group value=\''.$b.'\'>';
	echo 'DA:<input type=text name=da value="1.42">';
	echo 'NPA:<input type=text name=npa value="0.25">';
	echo 'HRA:<input type=text name=hra value="0.20">';
	echo 'Ceiling:<input type=text name=ceil value="85000">';
	echo '</form>';
	
}

if(isset($_POST['action']))
{
	if($_POST['action']=='edit_bill')
	{
		echo '<div align=center >';
		get_bill_group($link);
		echo '</div>';
	}
	if($_POST['action']=='E')
	{
		echo '<div >';
		display_staff($link,$_POST['staff_id']);
		display_calculate($link,$_POST['staff_id'] ,$_POST['bill_group']);
		edit_nonsalary($link,$_POST['staff_id'] ,$_POST['bill_group']);
		edit_salary($link,$_POST['staff_id'] ,$_POST['bill_group']);
		echo '</div>';
	}

	if($_POST['action']=='calculate')
	{
		recalculate($link,$_POST['staff_id'] ,$_POST['bill_group']);
		echo '<div>';
		display_staff($link,$_POST['staff_id']);
		display_calculate($link,$_POST['staff_id'] ,$_POST['bill_group']);
		edit_nonsalary($link,$_POST['staff_id'] ,$_POST['bill_group']);
		edit_salary($link,$_POST['staff_id'] ,$_POST['bill_group']);
		echo '</div>';
	}
		
	if($_POST['action']=='D')
	{
		delete_raw_by_id_dpc($link,'salary','staff_id',$_POST['staff_id'],'bill_group',$_POST['bill_group']);
		delete_raw_by_id_dpc($link,'nonsalary','staff_id',$_POST['staff_id'],'bill_group',$_POST['bill_group']);
	}
	
	if($_POST['action']=='C')
	{
		$staff_detail=get_raw($link, 'select * from staff where staff_id=\''.$_POST['staff_id'].'\'');
		echo '<form method=post>
		     <div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">';
		echo '<table  class="table table-striped ">';
		echo '<input type=hidden name=staff_id value=\''.$_POST['staff_id'].'\'>';
		echo '<input type=hidden name=bill_group value=\''.$_POST['bill_group'].'\'>';
		echo '<tr><th>Copy salary of</th><td>'.$staff_detail['fullname'].'</td></tr>';
		echo '<tr><th>From Bill</th><td>'.$_POST['bill_group'].'</td></tr>';
		echo '<tr><th>To Bill</th><td>';
		$sql='select bill_group from bill_group order by bill_group desc';
		mk_select_from_sql($link,$sql,'bill_group','to_bill_group','','');
		echo '</td></tr><tr><td  align=center colspan=2>';
		echo '<input class="btn btn-success" type=submit name=action value=select_to_bill>';
		echo '</td></tr></table></div></div></div></form>';				
	}
	if($_POST['action']=='select_to_bill')
	{
		copy_salary($link,$_POST['staff_id'],$_POST['bill_group'],$_POST['to_bill_group']);
		copy_nonsalary($link,$_POST['staff_id'],$_POST['bill_group'],$_POST['to_bill_group']);				
	}		
				
}

if(isset($_POST['bill_group']))
{
	echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">';
	list_bill($link,$_POST['bill_group']);
	echo '</div></div></div>';
}
htmltail();

?>

