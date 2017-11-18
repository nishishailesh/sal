<?php
session_start();
$nojunk='np';
require_once 'common.php';
//require_once 'common_js.php';

//print_r($_POST);
//////////////
$link=connect();

function email_one_staff($link,$bg,$si)
{

ob_start();
$str='<html><head></head><body>';
                        $str=$str.'<table border="1" align="center">';
                        $str=$str.'<tr><td>';
                                $str=$str.'<h3 align="center">Salary Slip, Government Medical College Surat</h3>';
                        $str=$str.'</td></tr>';
                        $str=$str.'<tr><td>';

                                $str=$str.'<table align="center" ><tr><td>';
			echo $str;$str='';
                                display_staff($link,$si);
                                $str=$str.'</td><td>';
			echo $str;$str='';
                                display_bill($link,$bg);
                                $str=$str.'</td></tr></table>';
                        $str=$str.'</td></tr>';
                        $str=$str.'<tr><td width="100%">';           
			echo $str;$str='';
                        print_one_nonsalary_slip($link,$si,$bg);
                        $str=$str.'</td></tr>';
                        $str=$str.'<tr><td>';
			echo $str;$str='';
                        print_one_salary_slip($link,$si,$bg);
                        $str=$str.'</td></tr>';
                        $str=$str.'<tr><td>';
                                //$str=$str.'<br><br><br><br><br><br><br>';  
                                $str=$str.'<table border="0" align="right">';
                                $str=$str.'<tr><td align="center">Account Officer</td></tr>';
                                $str=$str.'<tr><td align="center">Government Medical College</td></tr>';
                                $str=$str.'<tr><td align="center">Surat</td></tr>';
                                $str=$str.'</table>';
                        $str=$str.'</td></tr>';
                        $str=$str.'</table></body></html>';
			echo $str;$str='';
$output = ob_get_clean();
echo $output;
$email_to = "biochemistrygmcs@gmail.com";
//$email_to = "gandhi.ronak2@gmail.com";
$email_subject = 'Salary html last try today';
$email_message = $output;
 
    // create email headers
//    $headers = 'From: email@gmcsurat.edu.in'."\r\n".
//               'X-Mailer: PHP/' . phpversion();

$headers = 'MIME-Version: 1.0' . "\ r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\ r\n";

$headers .= 'From: email@gmcsurat.edu.in'."\r\n".

    'Reply-To: email@gmcsurat.edu.in'."\r\n" .

    'X-Mailer: PHP/' . phpversion();

     $result = mail($email_to, $email_subject, $email_message, $headers); 
 
     if ($result) echo 'Mail accepted for delivery HTML';
     if (!$result) echo 'Test unsuccessful... ';

			return $str;
  }

$str=email_one_staff($link,'12170701',213);
echo $str;




function print_one_nonsalary_slip($link,$staff_id,$bill_group,$format_table='')
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
		
		$ptbl=$ptbl.$t.'<td>'.$ar['name'].'</td>
										<td>'.$dt['data'].'</td>'.$tt;
		$count=$count+1;
	}
	

	$tbl='<table  width="100%" align=center id=nonsal class=border style="background-color:lightgray;display=block;">'.$ptbl.'</table>';
			
	echo $tbl;
}


function print_one_salary_slip($link,$staff_id,$bill_group,$format_table='')
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
								
		if($ar['type']=='+'){$ptbl=$ptbl.'<tr>
										<td width="65%">'.$ar['name'].'</td>
										<td width="15%">'.$dt['amount'].'</td>
										<td width="20%">'.$dt['remark'].'</td></tr>';}
										
		elseif($ar['type']=='-'){$mtbl=$mtbl.'<tr>
										<td width="65%">'.$ar['name'].'</td>
										<td width="15%">'.$dt['amount'].'</td>
										<td width="20%">'.$dt['remark'].'</td></tr>';}	
	}
	
	$tbl='<table width="100%" align=center id=sal class=border style="display=block;background-color:#A0CA94">	
				<tr><th>Payment</th><th>Deductions</th></tr>
				<tr><td valign=top><table class=border width="100%">'.$ptbl.'</table>
				</td><td valign=top><table class=border width="100%">'.$mtbl.'</table></td></tr>

		</table>';
			
	echo $tbl;
	$pmn=find_sums($link,$staff_id,$bill_group);

	echo '<table align=center><tr><td align=center><div align=center id="response">';
	
	echo '<table width="100%" class=border align="center" style="display:block;background:lightpink;"><tr>';
	echo '<th>Gross</th><th>Deductions</th><th>Net</th></tr><tr>';
	echo '<th>'.$pmn[0].'</th><th>'.$pmn[1].'</th><th>'.$pmn[2].'</th>';
	echo '</tr></table>';
	
	echo '</div></td></tr></table>';
}

?>

