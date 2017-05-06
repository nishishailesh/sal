<?php


if(!isset($nojunk))
{
	require_once 'common_js.php';
}

if(!isset($nojunk))
{
	require_once 'menu_salary.php';
}

require_once '/var/gmcs_config/staff.conf';

function login_varify()
{
	return mysqli_connect('127.0.0.1',$GLOBALS['main_user'],$GLOBALS['main_pass']);
}


/////////////////////////////////
function select_database($link)
{
	return mysqli_select_db($link,'c34');
}


function check_user($link,$u,$p)
{
	$sql='select * from user where id=\''.$u.'\'';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	$result_array=mysqli_fetch_assoc($result);
	if(md5($p)==$result_array['password'])
	{
		return true;
	}
	else
	{
		return false;
	}
}

function logout()
{
	session_start(); //Start the current session
	session_destroy(); //Destroy it! So we are logged out now
	header("location:".$GLOBALS['rootpath']."/common/index.php"); //configure absolute path of this file for access from anywhere
}
///////////////////////////////////
function connect()
{
	if(!$link=login_varify())
	{
		echo 'database login could not be verified<br>';
	
		exit();
	}


	if(!select_database($link))
	{
		echo 'database could not be selected<br>';
	
		exit();
	}
	
	if(!check_user($link,$_SESSION['login'],$_SESSION['password']))
	{
		echo 'application user could not be varified<br>';
		
		exit();
	}
	
return $link;
}

function mk_select_from_table($link,$field,$disabled,$default)
{
	$sql='select `'.$field.'` from '.$field;
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
		echo '<select  '.$disabled.' name='.$field.'>';
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

function mk_select_from_table_ajax($id,$idd,$link,$field,$disabled,$default,$size='')
{
	$sql='select `'.$field.'` from '.$field;
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
		echo '<select style="width:'.$size.'px;" '.$disabled.' name=\''.$field.'\'  id=\''.$field.'\' onchange="do_work(\''.$id.'\',this)"  >';
		while($result_array=mysqli_fetch_assoc($result))
		{
		if($result_array[$field]==$default)
		{
			echo '<option selected  value=\''.htmlspecialchars($result_array[$field]).'\'> '.$result_array[$field].' </option>';
		}
		else
			{
			echo '<option value=\''.htmlspecialchars($result_array[$field]).'\'> '.$result_array[$field].' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function mk_select_from_table_ajax_dpc($id,$idd,$link,$field,$disabled,$default,$size='')
{
	$sql='select `'.$field.'` from '.$field;
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
	echo '<select style="width:'.$size.'px;" '.$disabled.' name=\''.$field.'\'  id=\''.$field.'\' 
					onchange="do_work(\''.$id.'\',\''.$idd.'\',this)"  >';
		while($result_array=mysqli_fetch_assoc($result))
		{
			if($result_array[$field]==$default)
			{
			echo '<option selected  > '.$result_array[$field].' </option>';
			}
			else
			{
			echo '<option >'.$result_array[$field].' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}


function mk_select_from_sql($link,$sql,$field_name,$form_name,$disabled,$default)
{

	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
		echo '<select  '.$disabled.' name='.$form_name.' id='.$form_name.'>';
		while($result_array=mysqli_fetch_assoc($result))
		{
		if($result_array[$field_name]==$default)
		{
			echo '<option selected  > '.$result_array[$field_name].' </option>';
		}
		else
			{
				echo '<option  > '.$result_array[$field_name].' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function mk_select_from_array($ar,$form_name,$disabled,$default)
{

		echo '<select  '.$disabled.' name='.$form_name.' id='.$form_name.'>';
		foreach($ar as $value)
		{
			if($value==$default)
		{
			echo '<option selected  > '.$value.' </option>';
		}
		else
			{
				echo '<option  > '.$value.' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function mk_select_from_array_ajax($id,$ar,$form_name,$disabled,$default)
{

		echo '<select  onchange="do_work(\''.$id.'\',this)" '.$disabled.' name='.$form_name.' id='.$form_name.'>';
		foreach($ar as $value)
		{
			if($value==$default)
		{
			echo '<option selected  > '.$value.' </option>';
		}
		else
			{
				echo '<option  > '.$value.' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function mk_select_from_sql_with_separate_id($link,$sql,$field_name,$form_name,$id_name,$disabled,$default)
{

	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
		echo '<select  '.$disabled.' name='.$form_name.' id='.$id_name.'>';
		while($result_array=mysqli_fetch_assoc($result))
		{
		if($result_array[$field_name]==$default)
		{
			echo '<option selected  > '.$result_array[$field_name].' </option>';
		}
		else
			{
				echo '<option  > '.$result_array[$field_name].' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}


function combo_entry($link,$sql,$name,$disabled,$default)
{
	echo '<table><tr><td>';
	mk_select_from_sql($link,$sql,$name,$disabled,$default);
	echo '</td><td>';
	echo '<input type=text name=\'i_'.$name.'\'>';
	echo '<input type=checkbox name=\'ck_'.$name.'\'>';
	echo '</td></tr></table>';
	
}


function get_raw($link,$sql)
{
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	if(mysqli_num_rows($result)!=1){echo mysqli_error($link);return false;}
	else
	{
		return mysqli_fetch_assoc($result);
	}
}


function update_field_by_id($link,$table,$id_field,$id_value,$field,$value)
{
	$sql='update `'.$table.'` set `'.$field.'`=\''.$value.'\' where `'.$id_field.'`=\''.$id_value.'\'';
	//echo $sql;
	
	
	if(!$result=mysqli_query($link,$sql)){mysql_error();return FALSE;}
	else
	{
		return mysqli_affected_rows($link);
	}
}

function delete_raw_by_id($link,$table,$id_field,$id_value)
{
	$sql='delete from `'.$table.'` where `'.$id_field.'`=\''.$id_value.'\'';
	//echo $sql;
	
	
	if(!$result=mysqli_query($link,$sql)){mysql_error();return FALSE;}
	else
	{
		return mysqli_affected_rows($link);
	}
}

function delete_raw_by_id_dpc($link,$table,$id_field,$id_value,$id_fieldd,$id_valuee)
{
	$sql='delete from `'.$table.'` where 
		`'.$id_field.'`=\''.$id_value.'\'
			and
		`'.$id_fieldd.'`=\''.$id_valuee.'\'
		';
	//echo $sql;
	
	
	if(!$result=mysqli_query($link,$sql)){mysql_error();return FALSE;}
	else
	{
		return mysqli_affected_rows($link);
	}
}

function update_or_insert_field_by_id($link,$table,$id_field,$id_value,$field,$value)
{
	if(get_raw($link,'select `'.$id_field.'` from `'.$table.'` where `'.$id_field.'`=\''.$id_value.'\'')===FALSE)
	{
		//Try to insert
		$sqli='insert into `'.$table.'` (`'.$id_field.'`,`'.$field.'`) values (\''.$id_value.'\', \''.$value.'\')';
		//echo $sqli;
		if(!$resulti=mysqli_query($link,$sqli)){echo mysqli_error($link);return FALSE;}
		else
		{
			return mysqli_affected_rows($link);
		}
	}
	else
	{
		//Else update
		$sql='update `'.$table.'` set `'.$field.'`=\''.$value.'\' where `'.$id_field.'`=\''.$id_value.'\'';
		//echo $sql;
		if(!$result=mysqli_query($link,$sql))
		{
			echo mysqli_error($link);
			return FALSE;
		}
	}
}

function update_or_insert_field_by_id_tpc($link,$table,$id_field,$id_value,$id_fieldd,$id_valuee,$field,$value)
{
	if(get_raw($link,'select `'.$id_field.'` from `'.$table.'` 
				where       `'.$id_field.'`=\''.$id_value.'\'
						and `'.$id_fieldd.'`=\''.$id_valuee.'\' ')===FALSE)
	{
		//Try to insert
		$sqli='insert into `'.$table.'` (`'.$id_field.'`,`'.$field.'`) values (\''.$id_value.'\', \''.$value.'\')';
		//echo $sqli;
		if(!$resulti=mysqli_query($link,$sqli)){echo mysqli_error($link);return FALSE;}
		else
		{
			return mysqli_insert_id($link);
		}
	}
	else
	{
		//Else update
		$sql='update `'.$table.'` set `'.$field.'`=\''.$value.'\' where `'.$id_field.'`=\''.$id_value.'\' and `'.$id_fieldd.'`=\''.$id_valuee.'\'';
		//echo $sql;
		if(!$result=mysqli_query($link,$sql))
		{
			echo mysqli_error($link);
			return FALSE;
		}
		else
		{
			return mysqli_affected_rows($link);
		}
	}
}

function insert_field_by_id($link,$table,$id_field,$id_value,$field,$value)
{
		//Try to insert
		$sqli='insert into `'.$table.'` (`'.$id_field.'`,`'.$field.'`) values (\''.$id_value.'\', \''.$value.'\')';
		//echo $sqli;
		if(!$resulti=mysqli_query($link,$sqli)){echo mysqli_error($link);return FALSE;}
		else
		{
			return mysqli_insert_id($link);
		}
}


function insert_id($link,$table,$id_field,$id_value)
{
	$sqli='insert into `'.$table.'` (`'.$id_field.'`) values (\''.$id_value.'\')';
	//echo $sqli;
	if(!$resulti=mysqli_query($link,$sqli))
	{
		echo mysqli_error($link);return FALSE;
	}
	else
	{
		return mysqli_insert_id($link);
	}
}

function insert_id_tpc($link,$table,$id_field,$id_value,$id_fieldd,$id_valuee)
{
	$sqli='insert into `'.$table.'` (`'.$id_field.'`,`'.$id_fieldd.'`) values (\''.$id_value.'\',\''.$id_valuee.'\')';
	//echo $sqli;
	if(!$resulti=mysqli_query($link,$sqli))
	{
		echo mysqli_error($link);return FALSE;
	}
	else
	{
		return mysqli_insert_id($link);
	}
}

function update_or_insert_filename_field_by_id($link,$table,$id_field,$id_value,$field,$value)
{
	if(strlen($value)>0)
	{
		if(get_raw($link,'select `'.$id_field.'` from `'.$table.'` where `'.$id_field.'`=\''.$id_value.'\'')===FALSE)
		{
			//Try to insert
			$sqli='insert into `'.$table.'` (`'.$id_field.'`,`'.$field.'`) values (\''.$id_value.'\', \''.$value.'\')';
			echo $sqli;
			if(!$resulti=mysqli_query($link,$sqli)){echo mysqli_error($link);return FALSE;}
			else
			{
				return mysqli_affected_rows($link);
			}
		}
		else
		{
			//Else update
			$sql='update `'.$table.'` set `'.$field.'`=\''.$value.'\' where `'.$id_field.'`=\''.$id_value.'\'';
			//echo $sql;
			if(!$result=mysqli_query($link,$sql))
			{
				echo mysqli_error($link);
				return FALSE;
			}
		}
	}
}



function india_to_mysql_date($ddmmyyyy)
{
	$ex=explode('-',$ddmmyyyy);
	if(count($ex)==3)
	{
		return $ex[2].'-'.$ex[1].'-'.$ex[0];
	}
	else
	{
		return false;
	}
}

function mysql_to_india_date($yyyymmdd)
{
	$ex=explode('-',$yyyymmdd);
	if(count($ex)==3)
	{
		return $ex[2].'-'.$ex[1].'-'.$ex[0];
	}
	else
	{
		return false;
	}
}

function date_diff_to_year_month_days($from,$to)
{
	//dates as yyyy-mm-dd format only
	//To    2016-03-04
	//From  2015-05-20
	//      0000-09-(N) 
	
	$exf=explode('-',$from);
	$ext=explode('-',$to);
	if(count($exf)!=3||count($ext)!=3)
	{
		return false;
	}
	
	if(in_array('00',$exf)===TRUE || in_array('0000',$exf)===TRUE)
	{
		//print_r($exf);
		return false;
	}
	
	$days_of_from_month=cal_days_in_month(CAL_GREGORIAN,$exf[1],$exf[0]);
	if($days_of_from_month===FALSE)
	{
		return FALSE;
	}
	$days=$ext[2]+($days_of_from_month-$exf[2]);
	
	
	$months=$ext[1]+12-$exf[1]-1;
	
	$years=$ext[0]-$exf[0]-1;
	
	if($days>cal_days_in_month(CAL_GREGORIAN,$exf[1],$exf[0])){$days=abs($ext[2]-$exf[2]);$months=$months+1;}
	if($months>11){$years=$years+1;$months=$months-12;}
	
	//echo "<h1>".$to." and ".$from."</h1>";
	//echo "<h1>".$years.",".$months.",".$days."</h1>";
	
	return $years." yr, ".$months." mo, ".$days." d";
/*
	$y=$ext[0]-$exf[0];

	$m=$ext[1]-$exf[1];
	if($m<0){$y=$y-1;$m=12+$m;}
	
	$d=$ext[1]-$exf[1];
	if($d<0){$m=$m-1;$d=cal_days_in_month(CAL_GREGORIAN,$exf[1],$exf[0])-$d;}
	
	if($m<0){$y=$y-1;$m=12+$m;}
	
	echo "<h1>".$to." and ".$from."</h1>";
	echo "<h1>".$y.",".$m.",".$d."</h1>";
*/
}

//functions for file upload management//////////////

function file_to_str($link,$file)
{
	$fd=fopen($file['tmp_name'],'r');
	$size=$file['size'];
	$str=fread($fd,$size);
	return mysqli_real_escape_string($link,$str);
}

function insert_attachment($link,$table,$id_field,$id_value,$files_field,$files_value)
{
	$str=file_to_str($link,$files_value);

	$sql='insert into `'.$table.'` 
			(`'.$id_field.'`,`'.$files_field.'`) values(\''.$id_value.'\',"'.$str.'")';

		
	if(!$result=mysqli_query($link,$sql))
	{		
		//echo 'Error()';
		echo mysqli_error($link);
	}
	else
	{
		//echo 'insert success';
		return mysqli_insert_id($link);
	}
}	

function read_year($name,$y,$yy)
{
	echo '<select name=\''.$name.'\'>';
	for($i=$y;$i<$yy;$i++)
	{
			echo '<option>'.$i.'</option>';
	}
	echo '</select>';
	
}

function update_or_insert_attachment($link,$table,$id_field,$id_value,$files_field,$files_value)
{	
	//echo '<pre>'; print_r( $files_value);echo '</pre>';
	if($files_value['size']>0)
	{
		if(get_raw($link,'select `'.$id_field.'` from `'.$table.'` where `'.$id_field.'`=\''.$id_value.'\'')===FALSE)
		{
		//insert

			$str=file_to_str($link,$files_value);

			$sql='insert into `'.$table.'` 
					(`'.$id_field.'`,`'.$files_field.'`) values(\''.$id_value.'\',"'.$str.'")';

				
			if(!$result=mysqli_query($link,$sql))
			{		
				//echo 'Error()';
				echo mysqli_error($link);
			}
			else
			{
				//echo 'insert success';
				return mysqli_insert_id($link);
			}
		}
		//update
		else
		{
			$str=file_to_str($link,$files_value);
			$sql='update `'.$table.'` set 
					`'.$files_field.'` ="'.$str.'"
					where
					`'.$id_field.'` =\''.$id_value.'\'';
			//echo $sql;
			if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);}
			else
			{
				//echo 'update success';
				return $id_value;
			}			
		}
	}
}



function find_primary_key_array($link,$table)
{
	//This function is useful when primary key is madeup of multiple fields
	$sql_p='SHOW KEYS FROM  `'.$table.'` WHERE Key_name = \'PRIMARY\'';
	if(!$result_p=mysqli_query($link,$sql_p)){echo mysqli_error($link);return FALSE;}
	$pk=array();
	while($array_p=mysqli_fetch_assoc($result_p))
	{
		$pk[]=$array_p['Column_name'];
	}
	return $pk;
}

function read_primary_key($parray,$array)
{
	$ret_array=array();
	foreach($parray as $key=>$value)
	{
		$ret_array[$value]=$array[$value];
	}
	return $ret_array;
}

function prepare_where($ar)
{		
		if(count($ar)>0)
		{
			$where=' where ';
			foreach($ar as $k=>$v)
			{
				$where=$where.'`'.$k.'`='.'\''.$v.'\' and ';
			}
			$where=substr($where,0,-4);
		}
		else
		{
			$where='';
		}
		//echo '<h3>'.$where.'</h3>';
		return $where;
}

function prepare_where_like($ar)
{		
		if(count($ar)>0)
		{
			$where=' where ';
			foreach($ar as $k=>$v)
			{
				$where=$where.'`'.$k.'` like '.'\'%'.$v.'%\' and ';
			}
			$where=substr($where,0,-4);
		}
		else
		{
			$where='';
		}
		//echo '<h3>'.$where.'</h3>';
		return $where;
}


function display_photo($link,$photo)
{
		//if($ar['lng']>0)
		//{
			echo '<img style="width:3cm;height:4cm;" src="data:image/jpeg;base64,'.base64_encode($photo).'"/>';
		//}
		//else
		//{
		//	echo 'RECENT PHOTOGRAPH TO BE COUNTER SIGNED BY  THE DEAN/ PRINCIPAL';
		//}
}


function if_in_interval($dt,$from_dt,$to_dt)
{


	//f d t
	if(strtotime($dt)-strtotime($from_dt)>=0 && strtotime($dt)-strtotime($to_dt)<=0)
	{
		return 0;
	}
	
	//d f t
	elseif(strtotime($dt)-strtotime($from_dt)<0 && strtotime($dt)-strtotime($to_dt)<0)
	{
		return -1;
	}

	//f t d
	elseif(strtotime($dt)-strtotime($from_dt)>0 && strtotime($dt)-strtotime($to_dt)>0)
	{
		return 1;
	}
	
	//t f is illogical
	else
	{
		return FALSE;
	}
		
	/*

	$dtt=date_create($dt);
	$from_dtt=date_create($from_dt);
	$to_dtt=date_create($to_dt);
	
	$diff_from=date_diff($dtt,$from_dtt);
	print_r($diff_from);
	
	$diff_to=date_diff($dtt,$to_dtt);
	print_r($diff_to);	
	*/
	
}

function date_diff_grand($from_dt,$to_dt)
{
	$from_dtt=date_create($from_dt);
	$to_dtt=date_create($to_dt);
	
	return $diff_from=date_diff($from_dtt,$to_dtt);
	
	//echo '<pre>';
	//print_r($diff_from);
	//echo '</pre>';

}

function view_data_sql($link,$sql,$pk,$script)
{
	if(!$result_id=mysqli_query($link,$sql)){echo mysqli_error($link);}
	$array_id=mysqli_fetch_assoc($result_id);
	
	$first_data='yes';
	
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);}
	echo '<table border=1>';
	
	$first_data='yes';
	
	while($array=mysqli_fetch_assoc($result))
	{
		if($first_data=='yes')
		{
			echo '<tr bgcolor=lightgreen>';
			foreach($array as $key=>$value)
			{
				echo '<th>'.$key.'</hd>';
			}
			echo '</tr>';
			$first_data='no';
		}
		foreach($array as $key=>$value)
		{
			if($key==$pk)
			{
				echo '<td><form method=post action=\''.$script.'\'><button type=submit name=id value=\''.$value.'\'>'.$value.'</button></form></td>';			
			}
			else
			{
				echo '<td><pre>'.$value.'</pre></td>';
			}
		}
		echo '</tr>';

	}
	echo '</table>';
}

function get_user_info($link)
{
	$sql='select * from user where id=\''.$_SESSION['login'].'\'';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);}

	return mysqli_fetch_assoc($result);
}

//0 nothing
//1 read
//2 write  
//3 approve (and lock)
//4 ammend  (and unlock)

//0 nobody
//1 sister
//2 R1
//3 R2
//4 R3
//5 R4
//6 AP
//7 Asso
//8 Unit Head
//9 HOD

//0 other department
//1 same department

function get_user_right($id)
{
	$u=get_user_info($link);
	$pt=get_raw($link,'select * from pt where ipd=\''.$pt['ipd'].'\'');
	if($u['department']==$pt['department'] && $u['unit']==$pt['unit'])
	{
		return $u['right'];
	}
	else
	{
		return $u['right'];
	}
}


function export_to_csv($sql,$link)
{
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);}
	$fp = fopen('php://output', 'w');
	if ($fp && $result) 
	{
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="export.csv"');
		while ($row = mysqli_fetch_assoc($result))
		{
			fputcsv($fp, array_values($row));
		}
	}	
	
}

function get_staff_id($link)
{
$sql='select staff_id,fullname,uid from staff
order by fullname';

if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
echo '<select name=staff_id>';
while($ar=mysqli_fetch_assoc($result))
{
echo '<option value=\''.$ar['staff_id'].'\'>'.$ar['fullname'].'-'.$ar['staff_id'].'-'.$ar['uid'].'</option>';
}
echo '</select>';
}


function manage_single_salary($link,$staff_id,$bill_group)
{
	$slr=get_raw($link,'select * from salary
						where 
							salary.staff_id=\''.$staff_id.'\' 
							and  salary.bill_group=\''.$bill_group.'\' 
							');

	if($slr===FALSE)
	{
			insert_id_tpc($link,'salary','staff_id',$staff_id,'bill_group',$bill_group);
		
			$staff_detail=get_raw($link,'select * from staff where staff_id=\''.$staff_id.'\'');
			//print_r($staff_detail);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'fullname',$staff_detail['fullname']);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'department',$staff_detail['department']);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'post',$staff_detail['post']);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'gpf_acc',$staff_detail['gpf_acc']);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'cpf_acc',$staff_detail['cpf_acc']);											
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'bank',$staff_detail['bank']);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'bank_acc_number',$staff_detail['bank_acc_number']);
																																				
		$slr=get_raw($link,'select * from salary
							where 
								salary.staff_id=\''.$staff_id.'\' 
								and  salary.bill_group=\''.$bill_group.'\' 
								');
	}
	edit_salary($link,$slr);
}			


function new_salary($link,$staff_id,$bill_group)
{
	if($staff_id==0 || $bill_group==0){echo '<h5>Bill number can not be zero</h5>';return false;}
	$slr=get_raw($link,'select * from salary
						where 
							salary.staff_id=\''.$staff_id.'\' 
							and  salary.bill_group=\''.$bill_group.'\' 
							');

	if($slr===FALSE)
	{
			insert_id_tpc($link,'salary','staff_id',$staff_id,'bill_group',$bill_group);
		
			$staff_detail=get_raw($link,'select * from staff where staff_id=\''.$staff_id.'\'');
			//print_r($staff_detail);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'fullname',$staff_detail['fullname']);
			/*update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'department',$staff_detail['department']);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'post',$staff_detail['post']);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'gpf_acc',$staff_detail['gpf_acc']);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'cpf_acc',$staff_detail['cpf_acc']);											
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'bank',$staff_detail['bank']);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'bank_acc_number',$staff_detail['bank_acc_number']);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'pan',$staff_detail['pan']);
			update_or_insert_field_by_id_tpc($link,'salary',
												'staff_id',$staff_id,'bill_group',$bill_group,
												'quarter',$staff_detail['quarter']);*/

																																																																											
																																				
		$slr=get_raw($link,'select * from salary
							where 
								salary.staff_id=\''.$staff_id.'\' 
								and  salary.bill_group=\''.$bill_group.'\' 
								');
			display_salary($link,$slr);
	}
	else
	{
		if(isset($_POST['submit']))
		{
			display_salary($link,$slr);
		}
		else
		{
			echo '<h5>staff_id and bill_group combination exist. Can not create salary</h5>';
		}
	}
	

}			

function list_all_salary($link,$staff_id)
{

			echo '<table ><tr><td><h2>All Salary Slips of</h2></td><td>';
			display_staff($link,$_POST['staff_id']);
			echo '</td></tr></table>';
			
	$sql='select distinct bill_group from salary where staff_id=\''.$staff_id.'\'';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link); return FALSE;}	
	$header='yes';
	echo '<table align=center class=border style="background-color:#ADD8E6">';
	while($bg=mysqli_fetch_assoc($result))
	{
		$ar=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
		if($header=='yes')
		{
			echo '<tr><th>Action</th><th>Bill Group</th><th>Prepared on</th> <th>From</th> <th>To</th> <th>Head</th> 
			<th>Type</th><th>Remark</th></tr>
			<tr>';
			$header='no';
		}
			echo '<tr><td>';
			echo '<form  style="margin-bottom:0;" method=post>';
			echo '<input type=hidden name=staff_id value=\''.$staff_id.'\'>';
			echo '<input type=hidden name=bill_group value=\''.$ar['bill_group'].'\'>';
			echo '<input type=submit name=action value=E>';
			echo '<input type=submit name=action value=C>';
			echo '<input type=submit name=action value=D>';
			echo '</form></td>'.
			'<td>'.$ar['bill_group'].'</td>'.
			'<td>'.$ar['date_of_preparation'].'</td>'.
			'<td>'.$ar['from_date'].'</td>'.
			'<td>'.$ar['to_date'].'</td>'.
			'<td>'.$ar['head'].'</td>';
			'<td>'.$ar['bill_type'].'</td>'.
			'<td>'.$ar['remark'].'</td></tr>';
			
	}
	echo '</table>';

}

function display_salary_header($link)
{
	$sh=get_salary_head($link);
	$sql='desc salary';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo '<tr><td>Action</td>';
	while($ra=mysqli_fetch_assoc($result))
	{
			if(isset($sh[$ra['Field']])){$nm=$sh[$ra['Field']]['ooe'];}else{$nm='';}	
			echo '<th>'.$nm.' '.$ra['Field'].'</th>';
	}
	echo '</tr>';
}

function list_bill($link,$bill_group)
{
	echo '<table align=center><tr><td><h2>All Salary Slips of</h2></td><td>';
		display_bill($link,$_POST['bill_group']);
	echo '</td></tr></table>';
	$sql='select distinct salary.staff_id,fullname from salary,staff where 
					bill_group=\''.$bill_group.'\' 
					and 
					salary.staff_id=staff.staff_id';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	echo '<table align=center class=border style="background-color:#ADD8E6">';
	while($ar=mysqli_fetch_assoc($result))
	{
		echo '<tr><td>';
		echo '<form  style="margin-bottom:0;" method=post>';
		echo '<input type=hidden name=staff_id value=\''.$ar['staff_id'].'\'>';
		echo '<input type=hidden name=bill_group value=\''.$bill_group.'\'>';
		echo '<input type=submit name=action value=E>';
		echo '<input type=submit name=action value=C>';
		echo '<input type=submit name=action value=D onclick="return confirm(\'Salary will be deleted permanenty\')">';
		echo '</form>';
		echo '</td><td>'.$ar['fullname'].'</td></tr>';
	}
	echo '</table>';	
}

function old_edit_salary($link,$staff_id,$bill_group)
{
	$slr=get_raw($link,'select * from salary
							where 
								salary.staff_id=\''.$_POST['staff_id'].'\' 
								and  salary.bill_group=\''.$_POST['bill_group'].'\' 
								');				
	display_salary($link,$slr);
}

function get_salary_head($link)
{
	$sql='select * from salary_head';
	
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	while($ar=mysqli_fetch_assoc($result))
	{
			$ret[$ar['edp']]=$ar;
	}
	return $ret;
}


function display_salary($link,$slr)
{
	$sh=get_salary_head($link);
	
	if($slr===FALSE || count($slr)<=0){return false;}
	echo '<div onclick="hide(\'spn\')" id=response></div>';
	
	echo 	'<table class=border align=center style="horizontal-align:center;">
				<tr>
					<th colspan=6>Government Medical college Surat,';

	
	echo 			'</th>';
	echo '		</tr>
				<tr>';
	echo 			'<th>'.$slr['fullname'].'
					</th>
					<th>';
						mk_select_from_table_ajax_dpc($slr['staff_id'],$slr['bill_group'],$link,'department','',$slr['department']);
	echo 			'</th>
					<th>';
						mk_select_from_table_ajax_dpc($slr['staff_id'],$slr['bill_group'],$link,'post','',$slr['post']);	
	echo 			'</th>
				</tr>';
	echo 		'<tr>';
	//echo 			'<th>'.$slr['bank'].':'.$slr['bank_acc_number'].'</th>';
	
	echo 	'<th>Bank:';
	echo 				'<input
						style="text-align:left;"
						type=text 
						size=10 
						id=bank
						value=\''.$slr['bank'].'\' 
						onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this)" 
					>';	
	
	echo 	'Acc No:';
	echo 				'<input
						style="text-align:left;"
						type=text 
						size=10 
						id=bank_acc_number
						value=\''.$slr['bank_acc_number'].'\' 
						onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this)" 
					>';		
	echo	'</th>';	
	echo 	'<th>Bill Group:';
	echo 				'<input readonly
						style="text-align:left;"
						type=text 
						size=10 
						id=bill_group
						value=\''.$slr['bill_group'].'\' 
						onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this)" 
					>';
	echo	'</th>';

	echo 	'<th>Bill number:';
	echo 				'<input 
						style="text-align:left;"
						type=text 
						size=10 
						id=bill_number
						value=\''.$slr['bill_number'].'\' 
						onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this)" 
					>';	
	echo	'</th>';
	
	
	echo 		'	</tr>';
	echo 		'<tr>';
	
	echo 	'<th>GPF:';
	echo 				'<input
						style="text-align:left;"
						type=text 
						size=10 
						id=gpf_acc
						value=\''.$slr['gpf_acc'].'\' 
						onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this)" 
					>';
	echo	'</th>';	
	echo 	'<th>CPF:';
	echo 				'<input
						style="text-align:left;"
						type=text 
						size=10 
						id=cpf_acc
						value=\''.$slr['cpf_acc'].'\' 
						onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this)" 
					>';
	echo	'</th>';	
	
	echo	'<th>ID/AADHAR:'.$slr['staff_id'].'</th>
				</tr>';
	echo 		'<tr>';

	echo 	'<th>PAN:';
	echo 				'<input
						style="text-align:left;"
						type=text 
						size=10 
						id=pan
						value=\''.$slr['pan'].'\' 
						onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this)" 
					>';
	echo	'</th>';
	echo 	'<th>QTR:';
	echo 				'<input
						style="text-align:left;"
						type=text 
						size=10 
						id=quarter
						value=\''.$slr['quarter'].'\' 
						onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this)" 
					>';
	echo	'</th>';						
					
					
							
	echo			'<th>Head:';
	echo 				'<input 
						style="text-align:left;"
						type=text 
						size=10 
						id=budget_head
						value=\''.$slr['budget_head'].'\' 
						onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this)" 
					>';
	echo 			'</th>';
	echo 		'<tr>';
	echo 			'<th>From Date:<input type=text size=10 
							class=datepicker 
							id=from_date name=from_date 
							onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this);"
							value=\''.mysql_to_india_date($slr['from_date']).'\'></th>

					<th>To Date:<input type=text size=10 
							class=datepicker 
							id=to_date name=to_date  
							onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this);"
							value=\''.mysql_to_india_date($slr['to_date']).'\'></th>
					<th>Bill Type:';
					mk_select_from_table_ajax_dpc($slr['staff_id'],$slr['bill_group'],$link,'bill_type','',$slr['bill_type']);	
	echo			'</th></tr>';
	echo 						'<tr><th>Remark</th><th colspan=2 ><input placeholder=remark
									style="text-align:left;"
									type=text 
									size=50 
									id=remark
									value=\''.$slr['remark'].'\' 
									onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this)" 
								></th>';
	echo			'</tr>';	
	
	echo 		'</tr>';				
	echo 	'</table>';
	
	//echo '<input type=hidden name=staff_id value=\''.$slr['staff_id'].'\'>';
	//echo '<input type=hidden name=bill_group value=\''.$slr['bill_group'].'\'>';
	
	echo '<table align=center class=border><tr><td style=" vertical-align: top;"  colspan=2><table class=border>';
	
	$exclude=array('fullname','department','post','bank','bank_acc_number','bill_group',
						'budget_head','gpf_acc','cpf_acc','staff_id','remark',
						'staff_position_id','pan','quarter','bill_number','from_date','to_date','bill_type');

	$plus=0;
	$minus=0;

	
	foreach ($slr as $key=>$value)
	{
		if(!in_array($key,$exclude))
		{		
			
			if(isset($sh[$key])){$nm=$sh[$key]['ooe'];}else{$nm='';}
			
			if($key=='Income_Tax_9510(-)'){echo '</table></td><td colspan=2 style=" vertical-align: top;"><table class=border  >';}
			echo 		'<tr><td><b>'.$nm.' '.$key.'</b></td><td>
			<input 
				style="text-align:right;"
				type=text 
				size=10 
				id=\''.$key.'\'
				value=\''.$value.'\' 
				onchange="do_work(\''.$slr['staff_id'].'\',\''.$slr['bill_group'].'\',this)" 
			>
			</td></tr>';
			
			if(substr($key,-3)=='(+)'){$plus=$plus+$value;}
			elseif(substr($key,-3)=='(-)'){$minus=$minus+$value;}
		}
	}	
	echo '</table></td></tr><tr><td>';
//	echo '<table class=border align=center>';
		echo '<tr><td>Gross</td><td style="text-align:right;" >'.$plus.'</td><td>Deduction</td><td style="text-align:right;" >'.$minus.'</td></tr>';
	echo '<tr><td colspan=2>';
	
	echo '<form method=post>
		<input type=submit name=submit value=refresh>
		<input type=hidden name=staff_id value=\''.$_POST['staff_id'].'\'>
		<input type=hidden name=bill_group value=\''.$_POST['bill_group'].'\'>';
	echo '</form>';

	echo '</td><td>Net</td><td  style="text-align:right;" >'.($plus-$minus).'</td></tr>';
	
//	echo '</table>';
	echo '</td></tr></table>';
	
}

function select_bill_group($link)
{
	$sql='select distinct bill_group from salary order by bill_group desc';
	echo '<table class=border style="background-color:lightgreen;"><tr><th>Select Bill Number</th><td>';
	echo '<form method=post>';
	mk_select_from_sql($link,$sql,'bill_group','bill_group','','');
	echo '<input type=submit name=submit value=show>';
	echo '</form></td></tr></table>';
}


function copy_salary_old($link,$from_staff,$to_staff,$from_bn,$to_bn,$ar)
{
	//print_r($ar);
	//incomplate
	$sql='select * from salary where 
			staff_id=\''.$from_staff.'\' and
			bill_group=\''.$from_bn.'\'';
	//echo $sql;

	if(!$result=mysqli_query($link,$sql)){return FALSE;}

	while($ra=mysqli_fetch_assoc($result))
	{
		$f='';
		$v='';
		
		foreach($ra as $key=>$value)
		{
			//echo $key.'<br>';

			$f=$f.' `'.$key.'`,';
			
			if($key=='bill_group')
			{
				$v=$v.' \''.$to_bn.'\',';
			}
			elseif($key=='staff_id')
			{
					$v=$v.' \''.$to_staff.'\',';
			}
			elseif(array_key_exists($key,$ar))
			{
				$v=$v.' \''.$ar[$key].'\',';
			}
			else
			{
				$v=$v.' \''.$value.'\',';
			}
		}
		$f=substr($f,0,-1);
		$v=substr($v,0,-1);
		$final= 'insert into salary ('.$f.') values('.$v.')';
		//echo $final;

		if(!$result_s=mysqli_query($link,$final))
		{
			echo mysqli_error($link);return FALSE;
		}
		else
		{
			mysqli_insert_id($link);
		}
	}

}


function get_bill_group($link)
{
	echo '<form method=post>';
	echo '<table align=center class=border style="background-color:lightgreen;">';
	echo '<tr><th>Select Bill group</th><td>';
	echo '</td><tr><th>Bill group:</th><td>';
	$sql='select bill_group from bill_group order by bill_group desc';
	mk_select_from_sql($link,$sql,'bill_group','bill_group','','');
	echo '</td></tr><tr><td  align=center colspan=2>';
	echo '<input type=submit name=action value=select_bill_group>';
	echo '</td></tr></table></form>';
}

function get_bill_number($link,$bill_group)
{
	echo '<form method=post action=print_bill_step_2.php>';
	echo '<table class=border align=center style="background-color:lightgreen;">';
	echo '<tr><th>Bill Group</th><td><input type=text readonly name=bill_group value=\''.$bill_group.'\'</td></tr>';
	echo '</td><tr><th>Bill_number:</th><td>';
	$sql='select distinct data from nonsalary where bill_group=\''.$bill_group.'\' 
								and nonsalary_type_id=1
								order by data desc';
	//echo $sql;
	mk_select_from_sql($link,$sql,'data','bill_number','','');
	echo '</td></tr><tr><td  align=center colspan=2>';
	echo '<input type=submit name=submit value=print_reports>';
	echo '</td></tr></table></form>';
}

function display_bill($link,$bill_group,$header='yes')
{
	$sql='select * from bill_group where bill_group=\''.$bill_group.'\'';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	echo '<table align=center class=border style="background-color:#ADD8E6">';
	if($header=='yes')
	{
		echo '<tr><th>Bill Group</th><th>Prepared on</th> <th>From</th> <th>To</th> <th>Head</th> 
		<th>Type</th><th>Remark</th></tr>
		<tr>';
	}
	while($ar=mysqli_fetch_assoc($result))
	{
		echo '<td>'.$ar['bill_group'].'</td>'.
		'<td>'.$ar['date_of_preparation'].'</td>'.
		'<td>'.$ar['from_date'].'</td>'.
		'<td>'.$ar['to_date'].'</td>'.
		'<td>'.$ar['head'].'</td>';
		'<td>'.$ar['bill_type'].'</td>'.
		'<td>'.$ar['remark'].'</td>';
	}
	echo '</tr></table>';
}

function display_staff($link,$staff_id)
{
	$sql='select * from staff where staff_id=\''.$staff_id.'\'';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	echo '<table align=center class=border style="background-color:#F5D300">
	<tr><th>ID</th><th>Name</th></tr>
	<tr>';
	while($ar=mysqli_fetch_assoc($result))
	{
		echo '<td>'.$ar['staff_id'].'</td>'.
		'<td>'.$ar['fullname'].'</td>';
	}
	echo '</tr></table>';
}


function edit_salary($link,$staff_id,$bill_group,$format_table='')
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
								
		$title=$ar['salary_type_id'].'>>'.$ar['code1'].'>>'.$ar['code2'].'>>('.$ar['type'].')';
		if($ar['type']=='+'){$ptbl=$ptbl.'<tr><td title=\''.$title.'\' >'.$ar['name'].'</td>
										<td>
										<input size=7 
													onchange="do_work(\''.$staff_id.'\',
																	\''.$bill_group.'\',
																	this)"
													value=\''.$dt['amount'].'\'				
																	
													type=text 
													name=\''.$ar['salary_type_id'].'\' 
													id=\'ss_'.$ar['salary_type_id'].'\'
													>
										<input size=10
													placeholder=remark 
													onchange="do_work(\''.$staff_id.'\',
																	\''.$bill_group.'\',
																	this)"
													value=\''.$dt['remark'].'\'				
																	
													type=text 
													name=\'sr_'.$ar['salary_type_id'].'\' 
													id=\'sr_'.$ar['salary_type_id'].'\'
													>
										</td></tr>';}
		elseif($ar['type']=='-'){$mtbl=$mtbl.'<tr><td title=\''.$title.'\' >'.$ar['name'].'</td>
										<td>
										<input size=7 
													onchange="do_work(\''.$staff_id.'\',
																	\''.$bill_group.'\',
																	this)"
													value=\''.$dt['amount'].'\'				
																	
													type=text 
													name=\'ss_'.$ar['salary_type_id'].'\' 
													id=\'ss_'.$ar['salary_type_id'].'\'
													>
										<input size=10 
													placeholder=remark 
													onchange="do_work(\''.$staff_id.'\',
																	\''.$bill_group.'\',
																	this)"
													value=\''.$dt['remark'].'\'				
																	
													type=text 
													name=\'sr_'.$ar['salary_type_id'].'\' 
													id=\'sr_'.$ar['salary_type_id'].'\'
													>													
													
										</td></tr>';}	
	}
	
	$tbl='<table align=center id=sal class=border style="display=block;background-color:#A0CA94">	
				<tr><th>Payment</th><th>Deductions</th></tr>
				<tr><td valign=top><table class=border>'.$ptbl.'</table>
				</td><td valign=top><table class=border>'.$mtbl.'</table></td></tr>

		</table>';
			
	echo $tbl;
	$pmn=find_sums($link,$_POST['staff_id'],$_POST['bill_group']);

	echo '<table align=center><tr><td align=center><div align=center id="response">';
	
	echo '<table class=border align="center" style="display:block;background:lightpink;"><tr>';
	echo '<th>Gross</th><th>Deductions</th><th>Net</th></tr><tr>';
	echo '<th>'.$pmn[0].'</th><th>'.$pmn[1].'</th><th>'.$pmn[2].'</th>';
	echo '</tr></table>';
	
	echo '</div></td></tr></table>';
	
}

/*
function edit_nonsalary($link,$staff_id,$bill_group,$format_table='')
{
	if(strlen($format_table)==0){$format_table='nonsalary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$count=0;
	while($ar=mysqli_fetch_assoc($result))
	{
		$dt=get_raw($link,'select * from nonsalary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\' and 
								nonsalary_type_id=\''.$ar['nonsalary_type_id'].'\'');
		$title=$ar['nonsalary_type_id'];							 
		if($count%3==0){$t='<tr>';}else{$t='';}
		if($count%3==2){$tt='</tr>';}else{$tt='';}
		
		$ptbl=$ptbl.$t.'<td title=\''.$title.'\' >'.$ar['name'].'</td>
										<td>
										<input size=10 
													onchange="do_work(\''.$staff_id.'\',
																	\''.$bill_group.'\',
																	this)"
													value=\''.$dt['data'].'\'				
													type=text 
													name=\'ns_'.$ar['nonsalary_type_id'].'\' 
													id=\'ns_'.$ar['nonsalary_type_id'].'\'
													>										
										</td>'.$tt;
		$count=$count+1;
	}
	

	$tbl='<table  align=center id=nonsal class=border style="background-color:lightgray;display=block;">'.$ptbl.'</table>';
			
	echo $tbl;
}
*/




function mk_select_from_sql_nonsalary_ajax($link,$sql,$form_name,$pk1,$pk2,$default='',$disabled='',$size='')
{
	$str='';
	if(!$result=mysqli_query($link,$sql)){return FALSE;}
	
		$str=$str.'<select style="width:'.$size.'px;" '.$disabled.' 
				name=\''.$form_name.'\'  
				id=\''.$form_name.'\' 													
				onchange="do_work(\''.$pk1.'\',\''.$pk2.'\',this)"  >';
		while($result_array=mysqli_fetch_assoc($result))
		{
			if($result_array['f']==$default)
			{
				$str=$str.'<option selected  value=\''.htmlspecialchars($result_array['f']).'\'> '.$result_array['d'].' </option>';
			}
			else
			{
				$str=$str.'<option value=\''.htmlspecialchars($result_array['f']).'\'> '.$result_array['d'].' </option>';
			}
		}
		$str=$str.'</select>';	
		return $str;
}

function edit_nonsalary($link,$staff_id,$bill_group,$format_table='')
{
	if(strlen($format_table)==0){$format_table='nonsalary_type';}
	$sql='select * from `'.$format_table.'`';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ptbl='';
	$count=0;
	while($ar=mysqli_fetch_assoc($result))
	{
		$dt=get_raw($link,'select * from nonsalary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\' and 
								nonsalary_type_id=\''.$ar['nonsalary_type_id'].'\'');
		$title=$ar['nonsalary_type_id'];							 
		if($count%3==0){$t='<tr>';}else{$t='';}
		if($count%3==2){$tt='</tr>';}else{$tt='';}
		
		if($select_str=mk_select_from_sql_nonsalary_ajax($link,
						'select `'.$ar['name'].'` f ,
								`'.$ar['name'].'` d 
							from `'.$ar['name'].'`',
							'ns_'.$ar['nonsalary_type_id'],
							$staff_id,
							$bill_group,
							$dt['data'],'',
							120))
		{
			$ptbl=$ptbl.$t.'<td title=\''.$title.'\' >'.$ar['name'].'</td>
										<td>'.$select_str.'</td>'.$tt;
		}
		else
		{
		$ptbl=$ptbl.$t.'<td title=\''.$title.'\' >'.$ar['name'].'</td>
										<td>
										<input size=10 
													onchange="do_work(\''.$staff_id.'\',
																	\''.$bill_group.'\',
																	this)"
													value=\''.$dt['data'].'\'				
													type=text 
													name=\'ns_'.$ar['nonsalary_type_id'].'\' 
													id=\'ns_'.$ar['nonsalary_type_id'].'\'
													>										
										</td>'.$tt;
		}
		$count=$count+1;
	}
	

	$tbl='<table  align=center id=nonsal class=border style="background-color:lightgray;display=block;">'.$ptbl.'</table>';
			
	echo $tbl;
}


function find_sums($link,$staff_id,$bill_group)
{
	$sql='select * from salary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\'';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$p=0;
	$m=0;
	while($ar=mysqli_fetch_assoc($result))
	{	
		$dt=get_raw($link,'select * from salary_type where salary_type_id=\''.$ar['salary_type_id'].'\'');
		if($dt['type']=='+')
		{
			$p=$p+$ar['amount'];
		}
		elseif($dt['type']=='-')
		{
			$m=$m+$ar['amount'];
		}
	}	
	$n=$p-$m;	
	return array($p,$m,$n);				
}

function find_sums_govt($link,$staff_id,$bill_group)
{
	$sql='select * from salary where 
								staff_id=\''.$staff_id.'\' and 
								bill_group=\''.$bill_group.'\'';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$p=0;
	$m=0;
	while($ar=mysqli_fetch_assoc($result))
	{	
		$dt=get_raw($link,'select * from salary_type where salary_type_id=\''.$ar['salary_type_id'].'\'');
		if($dt['salary_type_id']==42 || $dt['salary_type_id']==43)
		{}
		elseif($dt['type']=='+')
		{
			$p=$p+$ar['amount'];
		}
		elseif($dt['type']=='-')
		{
			$m=$m+$ar['amount'];
		}
	}	
	$n=$p-$m;	
	return array($p,$m,$n);				
}

function add_bill_group($link,$post)
{
		/*
	bill_group 	bigint(11)	NO 	PRI 	NULL	
	date_of_preparation 	date	NO 		NULL	
	from_date 	date	NO 		NULL	
	to_date 	date	NO 		NULL	
	head 	varchar(100)	NO 		NULL	
	type 	varchar(100)	NO 		NULL	
	remark 	varchar(100)	NO 		NULL	
		 */

	$sql='
		insert into bill_group
		(
			bill_group,
			date_of_preparation,
			from_date,
			to_date,
			head,
			bill_type,
			remark
		)
		
		values
		(
			\''.$post['bill_group'].'\',
			\''.india_to_mysql_date($post['date_of_preparation']).'\',
			\''.india_to_mysql_date($post['from_date']).'\',
			\''.india_to_mysql_date($post['to_date']).'\',
			\''.$post['head'].'\',
			\''.$post['bill_type'].'\',
			\''.$post['remark'].'\'		
		)
			';
	//echo $sql;
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	return TRUE;
}

function copy_salary($link,$s,$b,$tb)
{
	$sql='select * from salary where staff_id=\''.$s.'\' and bill_group=\''.$b.'\'';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	while($ar=mysqli_fetch_assoc($result))
	{
		$sqls='insert into salary (staff_id,bill_group,salary_type_id,amount) 
						values(\''.$s.'\',\''.$tb.'\',\''.$ar['salary_type_id'].'\',\''.$ar['amount'].'\') 
						ON DUPLICATE KEY UPDATE    
						amount=\''.$ar['amount'].'\'';						
		if(!$rs=mysqli_query($link,$sqls)){echo mysqli_error($link);return FALSE;}
	}
}

function copy_bill_salary($link,$b,$tb)
{
	$sql='select * from salary where bill_group=\''.$b.'\'';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	while($ar=mysqli_fetch_assoc($result))
	{
		$sqls='insert into salary (staff_id,bill_group,salary_type_id,amount) 
						values(\''.$ar['staff_id'].'\',\''.$tb.'\',\''.$ar['salary_type_id'].'\',\''.$ar['amount'].'\') 
						ON DUPLICATE KEY UPDATE    
						amount=\''.$ar['amount'].'\'';						
		if(!$rs=mysqli_query($link,$sqls)){echo mysqli_error($link);return FALSE;}
	}
}

function copy_nonsalary($link,$s,$b,$tb)
{
	$sql='select * from nonsalary where staff_id=\''.$s.'\' and bill_group=\''.$b.'\'';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	while($ar=mysqli_fetch_assoc($result))
	{
		$sqls='insert into nonsalary (staff_id,bill_group,nonsalary_type_id,data) 
						values(\''.$s.'\',\''.$tb.'\',\''.$ar['nonsalary_type_id'].'\',\''.$ar['data'].'\') 
						ON DUPLICATE KEY UPDATE    
						data=\''.$ar['data'].'\'';		
						//echo $sqls;				
		if(!$rs=mysqli_query($link,$sqls)){echo mysqli_error($link);return FALSE;}
	}
}

function copy_bill_nonsalary($link,$b,$tb)
{
	$sql='select * from nonsalary where bill_group=\''.$b.'\'';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	while($ar=mysqli_fetch_assoc($result))
	{
		$sqls='insert into nonsalary (staff_id,bill_group,nonsalary_type_id,data) 
						values(\''.$ar['staff_id'].'\',\''.$tb.'\',\''.$ar['nonsalary_type_id'].'\',\''.$ar['data'].'\') 
						ON DUPLICATE KEY UPDATE    
						data=\''.$ar['data'].'\'';		
						//echo $sqls;				
		if(!$rs=mysqli_query($link,$sqls)){echo mysqli_error($link);return FALSE;}
	}
}

function get_sfval($link,$bg,$staff_id,$salary_type_id)
{
	$sql='select * from salary where 
				bill_group=\''.$bg.'\' and 
				staff_id=\''.$staff_id.'\' and
				salary_type_id=\''.$salary_type_id.'\'';
	//echo $sql_gpf;
	if(!$r=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$data=mysqli_fetch_assoc($r);
	return $data;	
}

function get_nsfval($link,$bg,$staff_id,$nonsalary_type_id)
{
	$sql='select * from nonsalary where 
				bill_group=\''.$bg.'\' and 
				staff_id=\''.$staff_id.'\' and
				nonsalary_type_id=\''.$nonsalary_type_id.'\'';
	//echo $sql_gpf;
	if(!$r=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$data=mysqli_fetch_assoc($r);
	return $data;	
}

function get_staff_of_a_bill_number($link,$bg,$bn)
{
	$sql='select * from nonsalary where 
				bill_group=\''.$bg.'\' and 
				nonsalary_type_id=1 and
				data=\''.$bn.'\'';
	//echo $sql;		
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	while($ar=mysqli_fetch_assoc($result))
	{
		$ret[]=$ar['staff_id'];
	}	
	return $ret;
}

function get_staff($link,$staff_id)
{
	$sql='select * from staff where 
				staff_id=\''.$staff_id.'\'';
	//echo $sql;		
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	return mysqli_fetch_assoc($result);
}

?>
