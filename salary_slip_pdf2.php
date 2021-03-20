<?php
session_start();
$nojunk='yes';
set_time_limit(360);
require_once 'common.php';
require_once('tcpdf/tcpdf.php');
//require_once('Numbers/Words.php');
//print_r($_POST);
//print_r($_POST);

	
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

$link=connect();

if(isset($_POST['from']))
{
	
	    ob_start();
		$sql='select distinct nonsalary.staff_id,fullname from nonsalary,staff where 
						bill_group=\''.$_POST['bill_group'].'\' 
						and 
						nonsalary.staff_id=staff.staff_id order by fullname limit '.$_POST['from'].','.$_POST['to'].'';
							
		if(!$result=mysqli_query($link,$sql)){return FALSE;}
		$tot=mysqli_num_rows($result);
		$count=1;
		while($result_array=mysqli_fetch_assoc($result))
		{
			print_one_complate_slip($link,$result_array,$_POST['bill_group']);
			if($count<$tot)
			{
				echo '<h2 style="page-break-after: always;"></h2>';
			}
			
			$count++;
		}

	$myStr = ob_get_contents();
	ob_end_clean();
   
 //echo $myStr;
  //exit(0);
    

	
	     $pdf = new ACCOUNT1('P', 'mm', 'A4', true, 'UTF-8', false);
	     $pdf->SetFont('dejavusans', '', 9);
	     $pdf->SetMargins(30, 20, 30);
	     $pdf->AddPage();
	     $pdf->writeHTML($myStr, true, false, true, false, '');
	     $pdf->Output($_POST['bill_group'].'_salary_slip_pdf2.pdf', 'I');
	 

	
}



?>


