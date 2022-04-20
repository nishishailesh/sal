<?php
session_start();
$nojunk='';
require_once 'common.php';
require_once('tcpdf/tcpdf.php');
//require_once('Numbers/Words.php');

class ACCOUNT1 extends TCPDF {

	public function Header() 
	{
	}
	
	public function Footer() 
	{
	    $this->SetY(-10);
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}	
}	
//print_r($_POST);
$link=connect();
//head();
//menu($link);


//list_annual_salary($link,$_POST['staff_id'],$_POST['fyear'],$_POST['fmonth'],$_POST['tyear'],$_POST['tmonth']);

vlist_annual_salary($link,$_POST['staff_id'],$_POST['fyear'],$_POST['fmonth'],$_POST['tyear'],$_POST['tmonth']);



function display_staff_big($link,$staff_id)
{
	$sql='select * from staff where staff_id=\''.$staff_id.'\'';

	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	$ar=mysqli_fetch_assoc($result);
	echo '<table style="font-size:2em;font-weight:bold;"><tr>';
	echo '<td>'.$ar['staff_id'].'</td><td>'.$ar['fullname'].'</td>';
	echo '</tr></table>';
}

function vlist_annual_salary($link,$staff_id,$fyear,$fmonth,$tyear,$tmonth)
{
	ob_start();
	display_staff_big($link,$staff_id);	
	$format_table='salary_type';
	$sql='select * from `'.$format_table.'` order by type,salary_type_id';
	if(!$result=mysqli_query($link,$sql)){echo mysqli_error($link);return FALSE;}
	
	$format_table='nonsalary_type';
	$sqln='select * from `'.$format_table.'` order by nonsalary_type_id';
	if(!$resultn=mysqli_query($link,$sqln)){echo mysqli_error($link);return FALSE;}
	

	//$sqll='select distinct bill_group from salary where staff_id=\''.$staff_id.'\' order by bill_group';

        $from=$fyear*100+$fmonth;
        $to=$tyear*100+$tmonth;
        $sqll='select 
                                distinct bill_group 
                        from 
                                salary 
                        where 
                                staff_id=\''.$staff_id.'\' 
                                and
                                (mod(bill_group,1000000)) DIV 100 between \''.$from.'\' and \''.$to.'\'
                        order by 
                                bill_group';


	if(!$resultt=mysqli_query($link,$sqll)){echo mysqli_error($link); return FALSE;}






	
	echo '<table border="0.3" style="border-collapse:collapse;text-align: right;font-family:monospaced;flex-wrap: nowrap">
	      <tr>
	          <td><b>Bill Group</b></td>';
	$bill_group=array();
	while($bg=mysqli_fetch_assoc($resultt))
	{ 
		echo '<td><b>'.$bg['bill_group'].'</b></td>';
		$bill_group[]=get_raw($link,'select * from bill_group where bill_group=\''.$bg['bill_group'].'\'');
	}
	echo '<td><b>Total</b></td></tr>';

	echo '<tr><td>Remark</td>';
	foreach($bill_group as $value)
	{
		echo '<td>'.$value['remark'].'</td>';
	}
	echo '<td></td></tr>';
	
	while($nst=mysqli_fetch_assoc($resultn))
	{	
		echo '<tr><td>'.substr($nst['shortform'],0,12).'</td>';
		foreach($bill_group as $value)
		{
		$raw=get_raw($link,'select * from nonsalary 
				where 
						bill_group=\''.$value['bill_group'].'\' 
						and staff_id=\''.$staff_id.'\' 
						and nonsalary_type_id=\''.$nst['nonsalary_type_id'].'\'');
		$dtt=isset($raw['data'])?$raw['data']:'';
		//echo '<td>'.substr($raw['data'],0,15).'</td>';
		echo '<td>'.substr($dtt,0,15).'</td>';
		}
		echo '<td></td></tr>';
	}
	
        $pmn0=0;
        echo '<tr><td><b>Payment</b></td>';
        foreach($bill_group as $value)
        {
                $pmn=find_sums($link,$staff_id,$value['bill_group']);
                echo '<td><b>'.$pmn[0].'</b></td>';
                $pmn0=$pmn0+$pmn[0];
        }
        echo '<td>'.$pmn0.'</td></tr>';

        $pmn1=0;
        echo '<tr><td><b>Deduction</b></td>';
        foreach($bill_group as $value)
        {
                $pmn=find_sums($link,$staff_id,$value['bill_group']);
                echo '<td><b>'.$pmn[1].'</b></td>';
                $pmn1=$pmn1+$pmn[1];
        }
        echo '<td>'.$pmn1.'</td></tr>';

        echo '<tr><td><b>Net</b></td>';
        

		$pmn2=0;
        foreach($bill_group as $value)
        {
                $pmn=find_sums($link,$staff_id,$value['bill_group']);
                echo '<td><b>'.$pmn[2].'</b></td>';
                $pmn2=$pmn2+$pmn[2];
        }
        echo '<td>'.$pmn2.'</td></tr>';


	while($st=mysqli_fetch_assoc($result))
	{	
		if($st['type']=='-')
		{
		//echo '<tr><td>('.$st['type'].')'.substr($st['shortfrom'],0,12).'</td>';
			echo '<tr><td><b>'.substr($st['shortform'],0,12).'</b></td>';
    	}
    	else
    	{    	
			echo '<tr><td>'.substr($st['shortform'],0,12).'</td>';
    	}
    	
    	$sum=0;
		foreach($bill_group as $value)
		{
			$raw=get_raw($link,'select * from salary 
				where 
						bill_group=\''.$value['bill_group'].'\' 
						and staff_id=\''.$staff_id.'\' 
						and salary_type_id=\''.$st['salary_type_id'].'\'');
			$amt=isset($raw['amount'])?$raw['amount']:0;
			//echo '<td>'.$raw['amount'].'</td>';
			echo '<td>'.$amt.'</td>';
			$sum=$sum+$amt;
		}
		echo '<td>'.$sum.'</td></tr>';
	}
	echo '</table>';
	
	$myStr = ob_get_contents();
	ob_end_clean();
   
 //echo $myStr;
//exit(0);
    

	
	     $pdf = new ACCOUNT1('L', 'mm', 'A3', true, 'UTF-8', false);
//	     $pdf->SetFont('dejavusans', '', 9);
	     $pdf->SetFont('dejavusans', '', $_POST['fontsize']);
//	     $pdf->SetFont('courier', '', 8);
	     $pdf->SetMargins(40, 10, 10);
	     $pdf->AddPage();
	     $pdf->writeHTML($myStr, true, false, true, false, '');
	     $pdf->Output($_POST['staff_id'].'annual_salary.pdf', 'I');
	 

}

htmltail();
?>


