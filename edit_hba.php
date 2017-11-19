<?php
session_start();
require_once 'common.php';

//print_r($_POST);

/////////////Main script start from here//////////////

$link=connect();

menu();

$GLOBALS['hba_p_id']=33;
$GLOBALS['hba_i_id']=40;

$sql_p='select * from salary where salary_type_id=\''.$GLOBALS['hba_p_id'].'\'';
$sql_i='select * from salary where salary_type_id=\''.$GLOBALS['hba_i_id'].'\'';

function edit_advance($link)
{
	$sql='select * from advance,staff where advance.staff_id=staff.staff_id order by status desc,type desc,fullname';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo '<table class=border>';
	echo '<tr style="background-color:lightgray;"><th>Name</th><th>ID</th><th>Acc</th><th>Acc Type</th> <th>Status</th><th>Amount</th><th>Instalment</th><th>Pre-Amount</th><th>Pre-installment</th><th>Action</th></tr>';
	while($ra=mysqli_fetch_assoc($result))
	{
		echo '<form method=post style="margin-bottom:0;"><tr>';
		echo '<td>'.$ra['fullname'].'</td><td>';
		echo '<input type=text size=10 readonly name=staff_id value=\''.$ra['staff_id'].'\'>';
		echo '</td>';
		echo '<td>';
		echo '<input type=text size=10 name=account_number value=\''.$ra['account_number'].'\'>';
		echo '</td>';
		echo '<td>';
		mk_select_from_array(array('interest','principle'),'type','',$ra['type']);
		//echo '<input type=text readonly size=10 name=type value=\''.$ra['type'].'\'>';
		echo '</td>';
		echo '<td>';
		mk_select_from_array(array('open','close'),'status','',$ra['status']);
		
		//echo '<input type=text size=10 name=status value=\''.$ra['status'].'\'>';
		echo '</td>';
		echo '<td>';
		echo '<input type=text size=10 name=amount value=\''.$ra['amount'].'\'>';
		echo '</td>';
		echo '<td>';
		echo '<input type=text size=10 name=installment value=\''.$ra['installment'].'\'>';
		echo '</td>';
				
		echo '<td>';
		echo '<input type=text size=10 name=pre_amount value=\''.$ra['pre_amount'].'\'>';
		echo '</td>';

		echo '<td>';
		echo '<input type=text size=10 name=pre_installment value=\''.$ra['pre_installment'].'\'>';
		echo '</td>';
		echo '<td>';
		echo '<button type=submit name=action value=save>S</button>';
		echo '<button type=submit name=action value=delete onclick="return confirm(\'Data will be deleted permanenty\')" >D</button>';
		echo '<button type=submit name=action value=view>V</button>';
		echo '</td>';
		echo '</tr></form>';
	}
	
		echo '<form method=post ><tr>';
		echo '<td colspan=2>';
		get_staff_id($link);
		echo '</td>';
		echo '<td>';
		echo '<input type=text size=10 name=account_number>';
		echo '</td>';
		echo '<td>';
		mk_select_from_array(array('interest','principle'),'type','','');
		echo '</td>';
		echo '<td>';
		mk_select_from_array(array('open','close'),'status','','');
		echo '<td>';
		echo '<input type=text size=10 name=amount>';
		echo '</td>';	
		
		echo '<td>';
		echo '<input type=text size=10 name=installment>';
		echo '</td>';	
		
		echo '<td>';
		echo '<input type=text size=10  name=pre_amount>';
		echo '</td>';
		echo '<td>';
		echo '<input type=text size=10 name=pre_installment>';
		echo '</td>';
		echo '<td>';
		echo '<button type=submit name=action value=save>S</button>';
		echo '</td>';
		echo '</tr></form>';
			
	
	echo '</table>';
}

function save($link)
{
$sql='insert into advance (staff_id,account_number,type,status,amount,installment,pre_amount,pre_installment) 
							values(	\''.$_POST['staff_id'].'\',
									\''.$_POST['account_number'].'\',
									\''.$_POST['type'].'\',
									\''.$_POST['status'].'\',
									\''.$_POST['amount'].'\',
									\''.$_POST['installment'].'\',
									\''.$_POST['pre_amount'].'\',
									\''.$_POST['pre_installment'].'\')
						
						ON DUPLICATE KEY UPDATE    
									account_number=\''.$_POST['account_number'].'\',
									status=\''.$_POST['status'].'\',
									amount=\''.$_POST['amount'].'\',
									installment=\''.$_POST['installment'].'\',
									pre_amount=\''.$_POST['pre_amount'].'\',
									pre_installment=\''.$_POST['pre_installment'].'\'';
	//echo $sql;
	
	if(!$result=mysqli_query($link,$sql)){echo mysql_error(); return FALSE;}
									
									
}

function del($link)
{
	$sql='delete from advance where staff_id=\''.$_POST['staff_id'].'\' and type=\''.$_POST['type'].'\'';
	
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysql_error(); return FALSE;}

}

function del_inst($link)
{
	$locked=is_bill_group_locked($link,$_POST['bill_group']);
	if($locked!=0){echo $_POST['bill_group'].' Bill Group locked'; return;  }
		
	//Array ( [staff_id] => 1002 [account_number] => 121212 [type] => interest [bill_group] => 34170501 [amount] => 2000 [remark] => 121212 [action] => delete_inst ) 
	$sql='';
		if($_POST['type']=='interest')
	{
		$sti=$GLOBALS['hba_i_id'];
	}
	elseif($_POST['type']=='principle')
	{
		$GLOBALS['hba_p_id'];
	}
		
	$sql='delete from salary where 
				staff_id=\''.$_POST['staff_id'].'\' and 
				salary_type_id=\''.$sti.'\' and
				bill_group=\''.$_POST['bill_group'].'\' ';
	
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysql_error(); return FALSE;}
}


function view($link)
{
	//echo strlen($_POST['account_number']);
	
	$sql='';
	if($_POST['type']=='interest')
	{
	$sql='select * from salary where staff_id=\''.$_POST['staff_id'].'\' 
				and 
					salary_type_id=\''.$GLOBALS['hba_i_id'].'\'
				and
					remark=\''.$_POST['account_number'].'\'
				order by bill_group desc';
	}
	elseif($_POST['type']=='principle')
	{
	$sql='select * from salary where staff_id=\''.$_POST['staff_id'].'\' 
				and 
					salary_type_id=\''.$GLOBALS['hba_p_id'].'\'
				and
					remark=\''.$_POST['account_number'].'\'
				order by bill_group desc';		
	}
	
	if($sql==''){return;}
	
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysql_error(); return FALSE;}
		$stf=get_staff($link,$_POST['staff_id']);
			echo '<table class=border>';
			echo '<tr style="background-color:lightblue;">	
						<th>'.$_POST['staff_id'].'</th>
						<th>'.$stf['fullname'].'</th>
						<th>'.$_POST['account_number'].'</th>
						<th>'.$_POST['type'].'</th>
					</tr>';
			echo '<tr style="background-color:lightgray;"><th>Bill Group</th>
					<th>Amount</th> <th>Remark</th><th>Action</th></tr>';
			
	while($ra=mysqli_fetch_assoc($result))
	{
		//echo strlen($ra['remark']);
		echo '<form method=post style="margin-bottom:0;"><tr>';
		echo '<input type=hidden name=staff_id value=\''.$_POST['staff_id'].'\'>
			  <input type=hidden name=account_number value=\''.$_POST['account_number'].'\'>
			  <input type=hidden name=type value=\''.$_POST['type'].'\'>';
		echo '<td>';
		echo '<input type=text size=10 readonly name=bill_group value=\''.$ra['bill_group'].'\'>';
		echo '</td>';
		echo '<td>';
		echo '<input type=text size=10 name=amount value=\''.$ra['amount'].'\'>';
		echo '</td>';
		echo '<td>';
		echo '<input type=text readonly size=10 name=remark value=\''.$ra['remark'].'\'>';
		echo '</td>';

		echo '<td>';
		echo '<button type=submit name=action value=save_inst>S</button>';
		echo '<button type=submit name=action value=delete_inst onclick="return confirm(\'Data will be deleted permanenty\')">D</button>';
		echo '</td>';
		echo '</tr></form>';
	}
		echo '<form method=post ><tr>';
		echo '<input type=hidden name=staff_id value=\''.$_POST['staff_id'].'\'>
			  <input type=hidden name=account_number value=\''.$_POST['account_number'].'\'>
			  <input type=hidden name=type value=\''.$_POST['type'].'\'>';
		echo '<td>';
			$sql='select bill_group from bill_group order by bill_group desc';
			mk_select_from_sql($link,$sql,'bill_group','bill_group','','');
		echo '</td>';
		echo '<td>';
		echo '<input type=text size=10 name=amount>';
		echo '</td>';
		echo '<td>';
		echo '<input type=text readonly size=10 name=remark>';
		echo '</td>';

		echo '<td>';
		echo '<button type=submit name=action value=save_inst>S</button>';
		echo '</td>';
		echo '</tr></form>';
			
	
	echo '</table>';

	$ac_detail=null;		
	$ac_sql='select * from advance where 
				staff_id=\''.$_POST['staff_id'].'\' and type=\''.$_POST['type'].'\'';
	$ac_detail=get_raw($link,$ac_sql);		
			
	//Array ( [tot_amount] => 12000 [tot_inst] => 3 ) 
	$sums_hba=get_hba_total_all_bill($link,$_POST['staff_id'],$_POST['type'],$_POST['account_number'])	;
	echo '<table class=border>';
	echo '<tr><th colspan=4 style="background-color:lightblue;">Summary</th></tr>';
	echo '<tr>';
	echo '<th>Total Installments paid:</th><td>'.($ac_detail['pre_installment']+$sums_hba['tot_inst']).'</td>';	
	echo '<th>Total amount recovered:</th><td>'.($ac_detail['pre_amount']+$sums_hba['tot_amount']).'</td>';
	echo '</tr>';
	echo '</table>';	
}	

function save_inst($link)
{
	$locked=is_bill_group_locked($link,$_POST['bill_group']);
	if($locked!=0){echo $_POST['bill_group'].' Bill Group locked'; return;  }

	if($_POST['type']=='interest')
	{
		//$GLOBALS['hba_p_id']=33;
		//$GLOBALS['hba_i_id']=40;
		$st=$GLOBALS['hba_i_id'];
	}
	elseif($_POST['type']=='principle')
	{
		$st=$GLOBALS['hba_p_id'];
	}
	else
	{
		return;
	}
	$sql='insert into salary (staff_id,bill_group,salary_type_id,amount,remark) 
							values(	\''.$_POST['staff_id'].'\',
									\''.$_POST['bill_group'].'\',
									\''.$st.'\',
									\''.$_POST['amount'].'\',
									\''.$_POST['account_number'].'\')
						
						ON DUPLICATE KEY UPDATE    
									amount=\''.$_POST['amount'].'\',
									remark=\''.$_POST['account_number'].'\'';
	//echo $sql;
	
	if(!$result=mysqli_query($link,$sql)){echo mysql_error(); return FALSE;}
	
	
}


if(isset($_POST['action']))
{
		if($_POST['action']=='save')
		{
			save($link);
		}
		if($_POST['action']=='delete')
		{
			del($link);
		}	
		if($_POST['action']=='save_inst')
		{
			save_inst($link);
		}	
		if($_POST['action']=='delete_inst')
		{
			del_inst($link);
		}	
}	

edit_advance($link);

if(isset($_POST['staff_id']) && isset($_POST['type']) && isset($_POST['account_number']))
{
	view($link);
	//print_r(get_hba_total($link,$_POST['staff_id'],$_POST['type'],$_POST['account_number']));
}

?>

