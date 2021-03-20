<?php
session_start();
$nojunk='defined';
require_once 'common.php';
require_once('tcpdf/tcpdf.php');
//require_once('Numbers/Words.php');
$link=connect();



	//apt-get install php-numbers-words
	///error may be:  undefined this in --> usr/share/php/Numbers/Words.php on line 97
	//Remove $this as follwos in like 97
	//$truth_table  = ($classname == get_class()) ? 'T' : 'F';
	
//rpp is raw per page
//echo '<pre>';

$GLOBALS['rpp']=15;
$GLOBALS['total_pages']='';
$GLOBALS['allowances']='Report on Pay and Allowances Bill';
$GLOBALS['deductions']='Report on Pay Bill Deductions';
$GLOBALS['cardex']='65';
$GLOBALS['ddo_no']='553';
$GLOBALS['grand']=array();
$GLOBALS['phone']='091-261-2244175';
$GLOBALS['mobile']='091 98244 19535';
$GLOBALS['ministry']='Health';
$GLOBALS['tan']='SRTG01499B';

//various id numbers as per database
//nonsaLARY
$GLOBALS['gpf_acc_id']=6;
$GLOBALS['cpf_acc_id']=7;
$GLOBALS['post_id']=3;
$GLOBALS['dept_id']=2;
$GLOBALS['ps_id']=10;
$GLOBALS['ops_id']=11;

//SALARY

$GLOBALS['pay_off_id']=1;			//12			
$GLOBALS['gp_id']=2;			//12
$GLOBALS['pay_est_id']=3;		//est
$GLOBALS['gp_e_id']=4;			//est
$GLOBALS['npa_id']=5;
$GLOBALS['ls_id']=6;
$GLOBALS['da_id']=7;
$GLOBALS['cla_id']=8;
$GLOBALS['hra_id']=9;
$GLOBALS['ma_id']=10;
$GLOBALS['ba_id']=11;
$GLOBALS['ta_id']=12;
$GLOBALS['ir_id']=13;
$GLOBALS['wa_id']=14;
$GLOBALS['ua_id']=15;
$GLOBALS['na_id']=16;
$GLOBALS['special_post_id']=17;
$GLOBALS['fp_id']=18;
$GLOBALS['ceil_id']=19;
$GLOBALS['itax_id']=20;
$GLOBALS['rob_id']=21;
$GLOBALS['pt_id']=22;
$GLOBALS['sis_if_id']=23;
$GLOBALS['sis_sf_id']=24;
$GLOBALS['gpf_id']=25;
$GLOBALS['gpf4_id']=26;
$GLOBALS['cpf_id']=27;
$GLOBALS['recv_off_id']=28;
$GLOBALS['recv_est_id']=29;
$GLOBALS['fes_id']=30;
$GLOBALS['food_id']=31;
$GLOBALS['car_id']=32;
$GLOBALS['hba_p_id']=33;
$GLOBALS['gpf_adv_recv_id']=39;	//non-IV
$GLOBALS['hba_i_id']=40;
$GLOBALS['infection_id']=41;
$GLOBALS['gmcs_soc_id']=42;
$GLOBALS['lic_id']=43;
$GLOBALS['gpf4_adv_recv_id']=46;	//IV

          

class ACCOUNT extends TCPDF {

	public function Header() 
	{
	}
	
	public function Footer() 
	{
				$this->SetY(-10);
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

	}	
}


$pdf = new ACCOUNT('L', 'mm', 'A3', true, 'UTF-8', false);
$pdf->SetFont('dejavusans', '', 9);
$pdf->SetMargins(30, 20, 30);

$pdf->AddPage();
outer_front($pdf);

$pdf->AddPage();
print_table($pdf,$link,$_POST['bill_group'],$_POST['bill_number']);

$total_pages=$pdf->getNumPages();
$pdf->setPage($total_pages);
//$pdf->AddPage();
outer_back($pdf,$link,$GLOBALS['net_for_back'],$_POST['bill_group']);
$pdf->Output($_POST['bill_group'].'_'.$_POST['bill_number'].'_outer.pdf', 'I');

function write_text($pdf,$text, $x,$y, $w,$h)
{
$pdf->SetFont('courier','B',10);
$pdf->SetXY($x,$y);
$pdf->SetTextColor(0);
$pdf->MultiCell($w, $h, $text , $border=0, $align='R', 
					$fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, 
					$ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);	
}
function write_text_small($pdf,$text, $x,$y, $w,$h)
{
$pdf->SetFont('courier','B',7);
$pdf->SetXY($x,$y);
$pdf->SetTextColor(0);
$pdf->MultiCell($w, $h, $text , $border=0, $align='R', 
					$fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, 
					$ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);	
}


function outer_front($pdf)
{
	//$img_file = 'outer_front.jpg';
	$img_file = 'jpg/outer_front_general_hlt.jpg';
//	$img_file = 'outer_front_general_hlt-70.jpg';
	$pdf->Image($img_file, 30, 20, 0, 0, '', '', '', false, 300, '', false, false, 0);


}

function outer_back($pdf,$link,$net,$bg)
{
		/**
	 * Puts an image in the page.
	 * The upper-left corner must be given.
	 * The dimensions can be specified in different ways:<ul>
	 * <li>explicit width and height (expressed in user unit)</li>
	 * <li>one explicit dimension, the other being calculated automatically in order to keep the original proportions</li>
	 * <li>no explicit dimension, in which case the image is put at 72 dpi</li></ul>
	 * Supported formats are JPEG and PNG images whitout GD library and all images supported by GD: GD, GD2, GD2PART, GIF, JPEG, PNG, BMP, XBM, XPM;
	 * The format can be specified explicitly or inferred from the file extension.<br />
	 * It is possible to put a link on the image.<br />
	 * Remark: if an image is used several times, only one copy will be embedded in the file.<br />
	 * @param $file (string) Name of the file containing the image or a '@' character followed by the image data string. To link an image without embedding it on the document, set an asterisk character before the URL (i.e.: '*http://www.example.com/image.jpg').
	 * @param $x (float) Abscissa of the upper-left corner (LTR) or upper-right corner (RTL).
	 * @param $y (float) Ordinate of the upper-left corner (LTR) or upper-right corner (RTL).
	 * @param $w (float) Width of the image in the page. If not specified or equal to zero, it is automatically calculated.
	 * @param $h (float) Height of the image in the page. If not specified or equal to zero, it is automatically calculated.
	 * @param $type (string) Image format. Possible values are (case insensitive): JPEG and PNG (whitout GD library) and all images supported by GD: GD, GD2, GD2PART, GIF, JPEG, PNG, BMP, XBM, XPM;. If not specified, the type is inferred from the file extension.
	 * @param $link (mixed) URL or identifier returned by AddLink().
	 * @param $align (string) Indicates the alignment of the pointer next to image insertion relative to image height. The value can be:<ul><li>T: top-right for LTR or top-left for RTL</li><li>M: middle-right for LTR or middle-left for RTL</li><li>B: bottom-right for LTR or bottom-left for RTL</li><li>N: next line</li></ul>
	 * @param $resize (mixed) If true resize (reduce) the image to fit $w and $h (requires GD or ImageMagick library); if false do not resize; if 2 force resize in all cases (upscaling and downscaling).
	 * @param $dpi (int) dot-per-inch resolution used on resize
	 * @param $palign (string) Allows to center or align the image on the current line. Possible values are:<ul><li>L : left align</li><li>C : center</li><li>R : right align</li><li>'' : empty string : left for LTR or right for RTL</li></ul>
	 * @param $ismask (boolean) true if this image is a mask, false otherwise
	 * @param $imgmask (mixed) image object returned by this function or false
	 * @param $border (mixed) Indicates if borders must be drawn around the cell. The value can be a number:<ul><li>0: no border (default)</li><li>1: frame</li></ul> or a string containing some or all of the following characters (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B: bottom</li></ul> or an array of line styles for each border group - for example: array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
	 * @param $fitbox (mixed) If not false scale image dimensions proportionally to fit within the ($w, $h) box. $fitbox can be true or a 2 characters string indicating the image alignment inside the box. The first character indicate the horizontal alignment (L = left, C = center, R = right) the second character indicate the vertical algnment (T = top, M = middle, B = bottom).
	 * @param $hidden (boolean) If true do not display the image.
	 * @param $fitonpage (boolean) If true the image is resized to not exceed page dimensions.
	 * @param $alt (boolean) If true the image will be added as alternative and not directly printed (the ID of the image will be returned).
	 * @param $altimgs (array) Array of alternate images IDs. Each alternative image must be an array with two values: an integer representing the image ID (the value returned by the Image method) and a boolean value to indicate if the image is the default for printing.
	 * @return image information
	 * @public
	 * @since 1.1
	 */
	 
	//$img_file = 'outer_back.jpg';
	//$img_file = 'outer_back_new.jpg';
	//$img_file = 'special_cut.jpg';
	//$img_file = 'special_cut_new.jpg';
	$img_file = 'special_cut_ltc.jpg';
	$pdf->Image($img_file, 30, 20, 0, 0, '', '', '', false, 300, '', false, false, 0);

	$bill_details=get_raw($link,'select * from bill_group where bill_group=\''.$bg.'\'');
	
	//$mynet='Received Contents Rs. '.$GLOBALS['net_for_back']. '(in Words)'.Numbers_Words::toWords($GLOBALS['net_for_back'],"en_US").' only';
	my_number_to_words($GLOBALS['net_for_back'],'yes');
	$mynet='Received Contents Rs. '.$GLOBALS['net_for_back']. '(in Words) '.$GLOBALS['n2s'].' only';
	
	write_text_fill_left($pdf,$mynet,115,42,100,10);
	
	//write_text_fill_left($pdf,'',78,200,28,10);
	//write_text_fill_left($pdf,'',98,185,24,10);

	//$date_str=date('m-y',strtotime($bill_details['from_date']));
	//write_text_fill_left($pdf,$date_str,288,130,14,5);
	//write_text_fill_left($pdf,$date_str,288,142,14,5);
	
	//$newdate=strtotime('-1 month',strtotime($bill_details['from_date']));
	//$newdate_str=date('m-y',$newdate);
	//write_text_fill_left($pdf,$newdate_str,315,103,14,5);
}


function plus_page_header($link,$bg,$bn,$pg)
{
	$bill_details=get_raw($link,'select * from bill_group where bill_group=\''.$bg.'\'');
	return '<table><tr><td width="20%"><h1>Bill No:'.$bg.'('.$bn.')</h1></td>
		  <td width="50%" align="center"><h1>'.$GLOBALS['college'].''.$GLOBALS['address'].''.$GLOBALS['city'].'<br>'.$GLOBALS['allowances'].'</h1></td>
		  <td width="30%" align="right"><h1>'.$bill_details['remark'].', Page No. ('.$pg.')</h1></td></tr></table>';
}

function minus_page_header($link,$bg,$bn,$pg)
{
	$bill_details=get_raw($link,'select * from bill_group where bill_group=\''.$bg.'\'');
	return '<table><tr><td width="20%"><h1>Bill No:'.$bg.'('.$bn.')</h1></td>
		  <td width="50%"  align="center"><h1>'.$GLOBALS['college'].''.$GLOBALS['address'].''.$GLOBALS['city'].'<br>'.$GLOBALS['deductions'].'</h1></td>
		  <td width="30%" align="right"><h1>'.$bill_details['remark'].', Page No. ('.$pg.')</h1></td></tr></table>';
}

function print_plus_tbl_hd()
{
		$plus_head='<tr>
		<th width="3%"><b>Sr</b></th>
		<th width="15%"><b>Emp Name<br>Dept,Post<br>7th Pay, 6th Pay</b></th>
		<th width="5%"><b>(002)GP<br>Special<br>FP</b></th>
		<th width="7%"><b>(001)(002)<br>P.Off<br>P.Est</b></th>
		<th width="5%"><b>(128)<br>NPA</b></th>
		<th width="5%"><b>(002)<br>LS/LE</b></th>
		<th width="5%"><b>(005)<br>DA</b></th>
		<th width="5%"><b>(006)<br>HRA</b></th>
		<th width="5%"><b>(016)<br>CLA</b></th>
		<th width="5%"><b>(009)<br>MA</b></th>
		<th width="5%"><b>(013)<br>TA</b></th>
		<th width="5%"><b>(104)<br>BA<br>Ceiling</b></th>
		<th width="5%"><b>(132)WA<br>(131)Uni<br>(129)Nur</b></th>
		<th width="7%"><b>Gross<br>Amt.</b></th>
		<th width="5%"><b>For Audit<br>Only</b></th>
		<th width="5%"><b>(510)ITx<br>(520)Sur</b></th>
	</tr><tr>
		<th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th>
	</tr>';
	return $plus_head;
	
}


function print_minus_tbl_hd()
{
	$ded_head='<tr>				
				<th width="3%"><b>Sr</b></th>
				<th width="6%"><b>(550)HRR<br>(560)RentB</b></th>
				<th width="5%"><b>(570)<br>Prof<br>Tax</b></th>
				<th width="5%"><b>(581)<br>SIS I/F<br>1981</b></th>
				<th width="5%"><b>(582)<br>SIS S/F<br>1981</b></th>
				<th width="5%"><b>(620)<br>GPF<br>non-IV</b></th>
				<th width="5%"><b>()<br>CPF</b></th>
				<th width="5%"><b>(013)<br>FES<br>Adv.</b></th>
				<th width="5%"><b>(014)<br>Food<br>Adv.</b></th>
				<th width="5%"><b>(592)<br>Car/Sct<br>Adv.</b></th>
				<th width="5%"><b>HBA<br>(590)Pri<br>(760)Int</b></th>
				<th width="5%"><b>(531)<br>GPF<br>CL-IV</b></th>
				<th width="5%"><b>Recv.</b></th>
				<th width="7%"><b>Tot.<br>Ded.</b></th>
				<th width="7%"><b>Net<br>Amt.</b></th>
				<th width="12%"><b>GPF<br>CPF<br>No</b></th>
			</tr><tr>
				<th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>31</th><th>32</th>
			</tr>';
			
	return $ded_head;		
	
	
}

$GLOBALS['sm']=array(); //plus
$GLOBALS['smm']=array();//minus

$GLOBALS['net_for_back']='';

function print_table($pdf,$link,$bg,$bn)
{
	//$s=get_staff_of_a_bill_number($link,$bg,$bn);
	$s=get_staff_of_a_bill_number_namewise($link,$bg,$bn);
	$total_row=count($s);
	$pstr='';
	$mstr='';
	$count=1;
	$pg=floor($count/$GLOBALS['rpp'])+1;
	$pstr=$pstr.plus_page_header($link,$bg,$bn,$pg);

	$mstr=$mstr.minus_page_header($link,$bg,$bn,$pg);

	$pstr=$pstr.'<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	$mstr=$mstr.'<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	$pstr=$pstr.print_plus_tbl_hd();
	$mstr=$mstr.print_minus_tbl_hd();
	
	foreach($s as $sr=>$staff_id)
	{
		
		//P2
		$staff=get_staff($link,$staff_id);
		$post_full=get_nsfval($link,$bg,$staff_id,$GLOBALS['post_id']);
		$post['data']=get_short_post($link,$post_full['data']);
		
		$dept=get_nsfval($link,$bg,$staff_id,$GLOBALS['dept_id']);
		$ps=get_nsfval($link,$bg,$staff_id,$GLOBALS['ps_id']);
		$ops=get_nsfval($link,$bg,$staff_id,$GLOBALS['ops_id']);
		//P3 = GP, Special Post, Family, Infection
		$gp_12=get_sfval($link,$bg,$staff_id,$GLOBALS['gp_id']);
		$gp_34=get_sfval($link,$bg,$staff_id,$GLOBALS['gp_e_id']);
		$gp=$gp_12['amount']+$gp_34['amount'];		//one of the two is zero
		if(!isset($GLOBALS['sm'][$pg]['gp'])){$GLOBALS['sm'][$pg]['gp']=0;}
		$GLOBALS['sm'][$pg]['gp']=$GLOBALS['sm'][$pg]['gp']+$gp;

		if(!isset($GLOBALS['sm'][$pg]['gp_12'])){$GLOBALS['sm'][$pg]['gp_12']=0;}
		$GLOBALS['sm'][$pg]['gp_12']=$GLOBALS['sm'][$pg]['gp_12']+$gp_12['amount'];
		if(!isset($GLOBALS['sm'][$pg]['gp_34'])){$GLOBALS['sm'][$pg]['gp_34']=0;}
		$GLOBALS['sm'][$pg]['gp_34']=$GLOBALS['sm'][$pg]['gp_34']+$gp_34['amount'];
		
		
		$sppa=get_sfval($link,$bg,$staff_id,$GLOBALS['special_post_id']);
		if(!isset($GLOBALS['sm'][$pg]['sppa'])){$GLOBALS['sm'][$pg]['sppa']=0;}
		$GLOBALS['sm'][$pg]['sppa']=$GLOBALS['sm'][$pg]['sppa']+$sppa['amount'];
		
		$fpa=get_sfval($link,$bg,$staff_id,$GLOBALS['fp_id']);
		if(!isset($GLOBALS['sm'][$pg]['fpa'])){$GLOBALS['sm'][$pg]['fpa']=0;}
		$GLOBALS['sm'][$pg]['fpa']=$GLOBALS['sm'][$pg]['fpa']+$fpa['amount'];
		
		$infection=get_sfval($link,$bg,$staff_id,$GLOBALS['infection_id']);
		if(!isset($GLOBALS['sm'][$pg]['infection'])){$GLOBALS['sm'][$pg]['infection']=0;}
		$GLOBALS['sm'][$pg]['infection']=$GLOBALS['sm'][$pg]['infection']+$infection['amount'];
		
		//P4
		$pay_off=get_sfval($link,$bg,$staff_id,$GLOBALS['pay_off_id']);
		if(!isset($GLOBALS['sm'][$pg]['pay_off'])){$GLOBALS['sm'][$pg]['pay_off']=0;}
		$GLOBALS['sm'][$pg]['pay_off']=$GLOBALS['sm'][$pg]['pay_off']+$pay_off['amount'];		

		$pay_est=get_sfval($link,$bg,$staff_id,$GLOBALS['pay_est_id']);
		if(!isset($GLOBALS['sm'][$pg]['pay_est'])){$GLOBALS['sm'][$pg]['pay_est']=0;}
		$GLOBALS['sm'][$pg]['pay_est']=$GLOBALS['sm'][$pg]['pay_est']+$pay_est['amount'];
		
		$basic=$pay_off['amount']+$pay_est['amount'];	//one of the two is zero
		//P5
		$npa=get_sfval($link,$bg,$staff_id,$GLOBALS['npa_id']);
		if(!isset($GLOBALS['sm'][$pg]['npa'])){$GLOBALS['sm'][$pg]['npa']=0;}
		$GLOBALS['sm'][$pg]['npa']=$GLOBALS['sm'][$pg]['npa']+$npa['amount'];
		//P6
		$ls=get_sfval($link,$bg,$staff_id,$GLOBALS['ls_id']);
		if(!isset($GLOBALS['sm'][$pg]['ls'])){$GLOBALS['sm'][$pg]['ls']=0;}
		$GLOBALS['sm'][$pg]['ls']=$GLOBALS['sm'][$pg]['ls']+$ls['amount'];
		//P7 DA
		$da=get_sfval($link,$bg,$staff_id,$GLOBALS['da_id']);
		if(!isset($GLOBALS['sm'][$pg]['da'])){$GLOBALS['sm'][$pg]['da']=0;}
		$GLOBALS['sm'][$pg]['da']=$GLOBALS['sm'][$pg]['da']+$da['amount'];				
		//P8 HRA
		$hra=get_sfval($link,$bg,$staff_id,$GLOBALS['hra_id']);
		if(!isset($GLOBALS['sm'][$pg]['hra'])){$GLOBALS['sm'][$pg]['hra']=0;}
		$GLOBALS['sm'][$pg]['hra']=$GLOBALS['sm'][$pg]['hra']+$hra['amount'];				
		//P9 CLA
		$cla=get_sfval($link,$bg,$staff_id,$GLOBALS['cla_id']);
		if(!isset($GLOBALS['sm'][$pg]['cla'])){$GLOBALS['sm'][$pg]['cla']=0;}
		$GLOBALS['sm'][$pg]['cla']=$GLOBALS['sm'][$pg]['cla']+$cla['amount'];				
		//P10 MA
		$ma=get_sfval($link,$bg,$staff_id,$GLOBALS['ma_id']);
		if(!isset($GLOBALS['sm'][$pg]['ma'])){$GLOBALS['sm'][$pg]['ma']=0;}
		$GLOBALS['sm'][$pg]['ma']=$GLOBALS['sm'][$pg]['ma']+$ma['amount'];	
		//P11 TA
		$ta=get_sfval($link,$bg,$staff_id,$GLOBALS['ta_id']);
		if(!isset($GLOBALS['sm'][$pg]['ta'])){$GLOBALS['sm'][$pg]['ta']=0;}
		$GLOBALS['sm'][$pg]['ta']=$GLOBALS['sm'][$pg]['ta']+$ta['amount'];	
		//P12 BA Ceil
		$ba=get_sfval($link,$bg,$staff_id,$GLOBALS['ba_id']);
		if(!isset($GLOBALS['sm'][$pg]['ba'])){$GLOBALS['sm'][$pg]['ba']=0;}
		$GLOBALS['sm'][$pg]['ba']=$GLOBALS['sm'][$pg]['ba']+$ba['amount'];	
	
		$ceil=get_sfval($link,$bg,$staff_id,$GLOBALS['ceil_id']);
		if(!isset($GLOBALS['sm'][$pg]['ceil'])){$GLOBALS['sm'][$pg]['ceil']=0;}
		$GLOBALS['sm'][$pg]['ceil']=$GLOBALS['sm'][$pg]['ceil']+$ceil['amount'];	
		//P13 WA UA NA
		$wa=get_sfval($link,$bg,$staff_id,$GLOBALS['wa_id']);
		if(!isset($GLOBALS['sm'][$pg]['wa'])){$GLOBALS['sm'][$pg]['wa']=0;}
		$GLOBALS['sm'][$pg]['wa']=$GLOBALS['sm'][$pg]['wa']+$wa['amount'];	

		$ua=get_sfval($link,$bg,$staff_id,$GLOBALS['ua_id']);
		if(!isset($GLOBALS['sm'][$pg]['ua'])){$GLOBALS['sm'][$pg]['ua']=0;}
		$GLOBALS['sm'][$pg]['ua']=$GLOBALS['sm'][$pg]['ua']+$ua['amount'];	

		$na=get_sfval($link,$bg,$staff_id,$GLOBALS['na_id']);
		if(!isset($GLOBALS['sm'][$pg]['na'])){$GLOBALS['sm'][$pg]['na']=0;}
		$GLOBALS['sm'][$pg]['na']=$GLOBALS['sm'][$pg]['na']+$na['amount'];	
		
		//P14
		//$sums_of_staff=find_sums($link,$staff_id,$bg);
		$sums_of_staff=find_sums_govt($link,$staff_id,$bg);
		$gross=$sums_of_staff[0];
		if(!isset($GLOBALS['sm'][$pg]['gross'])){$GLOBALS['sm'][$pg]['gross']=0;}
		$GLOBALS['sm'][$pg]['gross']=$GLOBALS['sm'][$pg]['gross']+$gross;	
				
		//P14
		//Empty
		
		//P16 ITAX (Actually minus, but to plus side
		$itax=get_sfval($link,$bg,$staff_id,$GLOBALS['itax_id']);
		if(!isset($GLOBALS['smm'][$pg]['itax'])){$GLOBALS['smm'][$pg]['itax']=0;}
		$GLOBALS['smm'][$pg]['itax']=$GLOBALS['smm'][$pg]['itax']+$itax['amount'];		
				
		//M17 serial number
					
		//M18 ROB
		$rob=get_sfval($link,$bg,$staff_id,$GLOBALS['rob_id']);
		if(!isset($GLOBALS['smm'][$pg]['rob'])){$GLOBALS['smm'][$pg]['rob']=0;}
		$GLOBALS['smm'][$pg]['rob']=$GLOBALS['smm'][$pg]['rob']+$rob['amount'];
		//M19 PT
		$pt=get_sfval($link,$bg,$staff_id,$GLOBALS['pt_id']);
		if(!isset($GLOBALS['smm'][$pg]['pt'])){$GLOBALS['smm'][$pg]['pt']=0;}
		$GLOBALS['smm'][$pg]['pt']=$GLOBALS['smm'][$pg]['pt']+$pt['amount'];
		//M20 SIS IF
		$sis_if=get_sfval($link,$bg,$staff_id,$GLOBALS['sis_if_id']);
		if(!isset($GLOBALS['smm'][$pg]['sis_if'])){$GLOBALS['smm'][$pg]['sis_if']=0;}
		$GLOBALS['smm'][$pg]['sis_if']=$GLOBALS['smm'][$pg]['sis_if']+$sis_if['amount'];
		//M21 SIS SF
		$sis_sf=get_sfval($link,$bg,$staff_id,$GLOBALS['sis_sf_id']);
		if(!isset($GLOBALS['smm'][$pg]['sis_sf'])){$GLOBALS['smm'][$pg]['sis_sf']=0;}
		$GLOBALS['smm'][$pg]['sis_sf']=$GLOBALS['smm'][$pg]['sis_sf']+$sis_sf['amount'];						
		//M22 GPF
		$gpf=get_sfval($link,$bg,$staff_id,$GLOBALS['gpf_id']);
		if(!isset($GLOBALS['smm'][$pg]['gpf'])){$GLOBALS['smm'][$pg]['gpf']=0;}
		$GLOBALS['smm'][$pg]['gpf']=$GLOBALS['smm'][$pg]['gpf']+$gpf['amount'];		
		
		$gpf_adv_recv=get_sfval($link,$bg,$staff_id,$GLOBALS['gpf_adv_recv_id']);
		if(!isset($GLOBALS['smm'][$pg]['gpf_adv_recv'])){$GLOBALS['smm'][$pg]['gpf_adv_recv']=0;}
		$GLOBALS['smm'][$pg]['gpf_adv_recv']=$GLOBALS['smm'][$pg]['gpf_adv_recv']+$gpf_adv_recv['amount'];		
				
		//M23 CPF
		$cpf=get_sfval($link,$bg,$staff_id,$GLOBALS['cpf_id']);
		if(!isset($GLOBALS['smm'][$pg]['cpf'])){$GLOBALS['smm'][$pg]['cpf']=0;}
		$GLOBALS['smm'][$pg]['cpf']=$GLOBALS['smm'][$pg]['cpf']+$cpf['amount'];						
		//M24 FES Recv
		$fes=get_sfval($link,$bg,$staff_id,$GLOBALS['fes_id']);
		if(!isset($GLOBALS['smm'][$pg]['fes'])){$GLOBALS['smm'][$pg]['fes']=0;}
		$GLOBALS['smm'][$pg]['fes']=$GLOBALS['smm'][$pg]['fes']+$fes['amount'];						
		//M25 Food Recv
		$food=get_sfval($link,$bg,$staff_id,$GLOBALS['food_id']);
		if(!isset($GLOBALS['smm'][$pg]['food'])){$GLOBALS['smm'][$pg]['food']=0;}
		$GLOBALS['smm'][$pg]['food']=$GLOBALS['smm'][$pg]['food']+$food['amount'];						
		//M26 Car Recv
		$car=get_sfval($link,$bg,$staff_id,$GLOBALS['car_id']);
		if(!isset($GLOBALS['smm'][$pg]['car'])){$GLOBALS['smm'][$pg]['car']=0;}
		$GLOBALS['smm'][$pg]['car']=$GLOBALS['smm'][$pg]['car']+$car['amount'];						
		//M27 HBA Int Pri
		$hba_i=get_sfval($link,$bg,$staff_id,$GLOBALS['hba_i_id']);
		if(!isset($GLOBALS['smm'][$pg]['hba_i'])){$GLOBALS['smm'][$pg]['hba_i']=0;}
		$GLOBALS['smm'][$pg]['hba_i']=$GLOBALS['smm'][$pg]['hba_i']+$hba_i['amount'];						

		$hba_p=get_sfval($link,$bg,$staff_id,$GLOBALS['hba_p_id']);
		if(!isset($GLOBALS['smm'][$pg]['hba_p'])){$GLOBALS['smm'][$pg]['hba_p']=0;}
		$GLOBALS['smm'][$pg]['hba_p']=$GLOBALS['smm'][$pg]['hba_p']+$hba_p['amount'];						
		//M28 GPF4
		$gpf4=get_sfval($link,$bg,$staff_id,$GLOBALS['gpf4_id']);
		if(!isset($GLOBALS['smm'][$pg]['gpf4'])){$GLOBALS['smm'][$pg]['gpf4']=0;}
		$GLOBALS['smm'][$pg]['gpf4']=$GLOBALS['smm'][$pg]['gpf4']+$gpf4['amount'];						

		$gpf4_adv_recv=get_sfval($link,$bg,$staff_id,$GLOBALS['gpf4_adv_recv_id']);
		if(!isset($GLOBALS['smm'][$pg]['gpf4_adv_recv'])){$GLOBALS['smm'][$pg]['gpf4_adv_recv']=0;}
		$GLOBALS['smm'][$pg]['gpf4_adv_recv']=$GLOBALS['smm'][$pg]['gpf4_adv_recv']+$gpf4_adv_recv['amount'];	
		
		
		//M29 Recovery of Off and EST

		$recv_off=get_sfval($link,$bg,$staff_id,$GLOBALS['recv_off_id']);
		if(!isset($GLOBALS['smm'][$pg]['recv_off'])){$GLOBALS['smm'][$pg]['recv_off']=0;}
		$GLOBALS['smm'][$pg]['recv_off']=$GLOBALS['smm'][$pg]['recv_off']+$recv_off['amount'];

		$recv_est=get_sfval($link,$bg,$staff_id,$GLOBALS['recv_est_id']);
		if(!isset($GLOBALS['smm'][$pg]['recv_est'])){$GLOBALS['smm'][$pg]['recv_est']=0;}
		$GLOBALS['smm'][$pg]['recv_est']=$GLOBALS['smm'][$pg]['recv_est']+$recv_est['amount'];						

		//Soc LIC
		$gmcs_soc=get_sfval($link,$bg,$staff_id,$GLOBALS['gmcs_soc_id']);
		if(!isset($GLOBALS['smm'][$pg]['gmcs_soc'])){$GLOBALS['smm'][$pg]['gmcs_soc']=0;}
		$GLOBALS['smm'][$pg]['gmcs_soc']=$GLOBALS['smm'][$pg]['gmcs_soc']+$gmcs_soc['amount'];	
		$lic=get_sfval($link,$bg,$staff_id,$GLOBALS['lic_id']);
		if(!isset($GLOBALS['smm'][$pg]['lic'])){$GLOBALS['smm'][$pg]['lic']=0;}
		$GLOBALS['smm'][$pg]['lic']=$GLOBALS['smm'][$pg]['lic']+$lic['amount'];	
								
		//M30 Deductions
		$tot_ded=$sums_of_staff[1];
		if(!isset($GLOBALS['smm'][$pg]['tot_ded'])){$GLOBALS['smm'][$pg]['tot_ded']=0;}
		$GLOBALS['smm'][$pg]['tot_ded']=$GLOBALS['smm'][$pg]['tot_ded']+$tot_ded;			
		//M31 Net
		$tot_net=$sums_of_staff[2];
		if(!isset($GLOBALS['smm'][$pg]['tot_net'])){$GLOBALS['smm'][$pg]['tot_net']=0;}
		$GLOBALS['smm'][$pg]['tot_net']=$GLOBALS['smm'][$pg]['tot_net']+$tot_net;			
		//M32 GPF CPF No
		$gpf_acc=get_nsfval($link,$bg,$staff_id,$GLOBALS['gpf_acc_id']);
		$cpf_acc=get_nsfval($link,$bg,$staff_id,$GLOBALS['cpf_acc_id']);
		
		//main table		
			$pstr=$pstr.'<tr>
					<td>'.$count.'</td>				
					<td>'.$staff['fullname'].'<br>'.$dept['data'].','.$post['data'].'<br>'.$ps['data'].','.$ops['data'].'</td>
					<td>'.$gp.',<br>'.$sppa['amount'].',<br>'.$fpa['amount'].','.$infection['amount'].'</td>		
					<td>'.$basic.'</td>						
					<td>'.$npa['amount'].'</td>				
					<td>'.$ls['amount'].'</td>				
					<td>'.$da['amount'].'</td>				
					<td>'.$hra['amount'].'</td>				
					<td>'.$cla['amount'].'</td>				
					<td>'.$ma['amount'].'</td>				
					<td>'.$ta['amount'].'</td>				
					<td>'.$ba['amount'].'<br>'.$ceil['amount'].'</td>	
					<td>'.$wa['amount'].'<br>'.$ua['amount'].'<br>'.$na['amount'].'</td>	
					<td>'.$gross.'</td>				
					<td></td>
					<td>'.$itax['amount'].'</td>				

				</tr>';
			$mstr=$mstr.'<tr>
					<td>'.$count.'<br><br></td>
					<td>'.$rob['amount'].'</td>			
					<td>'.$pt['amount'].'</td>			
					<td>'.$sis_if['amount'].'</td>			
					<td>'.$sis_sf['amount'].'</td>	
					<td>'.$gpf['amount'].',<br>'.$gpf_adv_recv['amount'].'</td>			
					<td>'.$cpf['amount'].'</td>			
					<td>'.$fes['amount'].'</td>			
					<td>'.$food['amount'].'</td>			
					<td>'.$car['amount'].'</td>			
					<td>'.$hba_p['amount'].',<br>'.$hba_i['amount'].'</td>			
					<td>'.$gpf4['amount'].',<br>'.$gpf4_adv_recv['amount'].'</td>			
					<td>'.($recv_off['amount']+$recv_est['amount']).'</td>			
					<td>'.$tot_ded.'</td>			
					<td>'.$tot_net.'</td>			
					<td>'.$gpf_acc['data'].',<br>'.$cpf_acc['data'].'</td>			
		
							
				</tr>';
				
			if(	$count%$GLOBALS['rpp']==0 || $count==$total_row)
			{
				$pstr=$pstr.'<tr>
						<td>Page:'.$pg.'</td>	
						<td></td>
						<td>'.($GLOBALS['sm'][$pg]['gp']+$GLOBALS['sm'][$pg]['sppa']+
								$GLOBALS['sm'][$pg]['fpa']+$GLOBALS['sm'][$pg]['infection']).'</td>
						<td>'.($GLOBALS['sm'][$pg]['pay_off']+$GLOBALS['sm'][$pg]['pay_est']).'</td>	
						<td>'.$GLOBALS['sm'][$pg]['npa'].'</td>
						<td>'.$GLOBALS['sm'][$pg]['ls'].'</td>
						<td>'.$GLOBALS['sm'][$pg]['da'].'</td>
						<td>'.$GLOBALS['sm'][$pg]['hra'].'</td>
						<td>'.$GLOBALS['sm'][$pg]['cla'].'</td>
						<td>'.$GLOBALS['sm'][$pg]['ma'].'</td>
						<td>'.$GLOBALS['sm'][$pg]['ta'].'</td>
						<td>'.($GLOBALS['sm'][$pg]['ba']+$GLOBALS['sm'][$pg]['ceil']).'</td>
						<td>'.($GLOBALS['sm'][$pg]['wa']+$GLOBALS['sm'][$pg]['ua']+$GLOBALS['sm'][$pg]['na']).'</td>
						<td>'.$GLOBALS['sm'][$pg]['gross'].'</td>
						<td></td>
						<td>'.$GLOBALS['smm'][$pg]['itax'].'</td>
						

					</tr>';
				$mstr=$mstr.'<tr>
						<td>Page:'.$pg.'</td>	
						<td>'.$GLOBALS['smm'][$pg]['rob'].'</td>			
						<td>'.$GLOBALS['smm'][$pg]['pt'].'</td>			
						<td>'.$GLOBALS['smm'][$pg]['sis_if'].'</td>			
						<td>'.$GLOBALS['smm'][$pg]['sis_sf'].'</td>			
						<td>'.($GLOBALS['smm'][$pg]['gpf']+$GLOBALS['smm'][$pg]['gpf_adv_recv']).'</td>			
						<td>'.$GLOBALS['smm'][$pg]['cpf'].'</td>			
						<td>'.$GLOBALS['smm'][$pg]['fes'].'</td>			
						<td>'.$GLOBALS['smm'][$pg]['food'].'</td>			
						<td>'.$GLOBALS['smm'][$pg]['car'].'</td>			
						<td>'.($GLOBALS['smm'][$pg]['hba_p']+$GLOBALS['smm'][$pg]['hba_i']).'</td>
						<td>'.($GLOBALS['smm'][$pg]['gpf4']+$GLOBALS['smm'][$pg]['gpf4_adv_recv']).'</td>
						<td>'.($GLOBALS['smm'][$pg]['recv_off']+$GLOBALS['smm'][$pg]['recv_est']).'</td>			
						<td>'.$GLOBALS['smm'][$pg]['tot_ded'].'</td>			
						<td>'.$GLOBALS['smm'][$pg]['tot_net'].'</td>			
						<td></td>			
					</tr>';
				$pstr=$pstr.'</table>';
				$mstr=$mstr.'</table>';
				
				$pstr=$pstr.'<h2 style="page-break-after: always;"></h2>';
				$mstr=$mstr.'<h2 style="page-break-after: always;"></h2>';
				$pdf->writeHTML($pstr, true, false, true, false, '');
				$pdf->writeHTML($mstr, true, false, true, false, '');
				
				$pstr='';
				$mstr='';
				$pg=floor($count/$GLOBALS['rpp'])+1;
				$pstr=$pstr.plus_page_header($link,$bg,$bn,$pg);
				$mstr=$mstr.minus_page_header($link,$bg,$bn,$pg);

				$pstr=$pstr.'<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
				$mstr=$mstr.'<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';	
				$pstr=$pstr.print_plus_tbl_hd();
				$mstr=$mstr.print_minus_tbl_hd();			
			}
			$count++;
	}


	if($count>$GLOBALS['rpp'])
	{
	
	//plus summary
	$sm_str='';
	$sm_str=$sm_str.plus_page_header($link,$bg,$bn,'Summary');
	$sm_str=$sm_str.'<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	$sm_str=$sm_str.print_plus_tbl_hd();
	foreach($GLOBALS['sm'] as $rn=>$r)
	{
			$sm_str=$sm_str.'<tr>
				<td></td>
				<td>Page:'.$rn.'<br></td>	
						<td>'.($GLOBALS['sm'][$rn]['gp']+$GLOBALS['sm'][$rn]['sppa']+
								$GLOBALS['sm'][$rn]['fpa']+$GLOBALS['sm'][$rn]['infection']).'</td>
				<td>'.($GLOBALS['sm'][$rn]['pay_off']+$GLOBALS['sm'][$rn]['pay_est']).'</td>
				<td>'.$GLOBALS['sm'][$rn]['npa'].'</td>	
				<td>'.$GLOBALS['sm'][$rn]['ls'].'</td>
				<td>'.$GLOBALS['sm'][$rn]['da'].'</td>
				<td>'.$GLOBALS['sm'][$rn]['hra'].'</td>
				<td>'.$GLOBALS['sm'][$rn]['cla'].'</td>	
				<td>'.$GLOBALS['sm'][$rn]['ma'].'</td>
				<td>'.$GLOBALS['sm'][$rn]['ta'].'</td>
				<td>'.($GLOBALS['sm'][$rn]['ba']+$GLOBALS['sm'][$rn]['ceil']).'</td>		
				<td>'.($GLOBALS['sm'][$rn]['wa']+$GLOBALS['sm'][$rn]['ua']+$GLOBALS['sm'][$rn]['na']).'</td>							
				<td>'.$GLOBALS['sm'][$rn]['gross'].'</td>
				<td></td>
				<td>'.$GLOBALS['smm'][$rn]['itax'].'</td>
							</tr>';
	}
	
	$sm_str=$sm_str.'<tr>
				<td></td>
				<td>Total</td>	
				<td>'.	(array_sum(array_column($GLOBALS['sm'],'gp'))+
						array_sum(array_column($GLOBALS['sm'],'sppa'))+
						array_sum(array_column($GLOBALS['sm'],'fpa'))+
						array_sum(array_column($GLOBALS['sm'],'infection'))).'</td>	
				<td>'.(	array_sum(array_column($GLOBALS['sm'],'pay_off'))+
						array_sum(array_column($GLOBALS['sm'],'pay_est'))	).'</td>
				<td>'.array_sum(array_column($GLOBALS['sm'],'npa')).'</td>	
				<td>'.array_sum(array_column($GLOBALS['sm'],'ls')).'</td>	
				<td>'.array_sum(array_column($GLOBALS['sm'],'da')).'</td>	
				<td>'.array_sum(array_column($GLOBALS['sm'],'hra')).'</td>	
				<td>'.array_sum(array_column($GLOBALS['sm'],'cla')).'</td>	
				<td>'.array_sum(array_column($GLOBALS['sm'],'ma')).'</td>	
				<td>'.array_sum(array_column($GLOBALS['sm'],'ta')).'</td>	
				<td>'.(array_sum(array_column($GLOBALS['sm'],'ba'))+
						array_sum(array_column($GLOBALS['sm'],'ceil'))).'</td>	
				<td>'.(array_sum(array_column($GLOBALS['sm'],'wa'))+
						array_sum(array_column($GLOBALS['sm'],'ua'))+
						array_sum(array_column($GLOBALS['sm'],'na'))).'</td>				
				<td>'.array_sum(array_column($GLOBALS['sm'],'gross')).'</td>	
				<td></td>
				<td>'.array_sum(array_column($GLOBALS['smm'],'itax')).'</td>	

							</tr>';
			
	$sm_str=$sm_str.'</table>';
	$sm_str=$sm_str.'<h2 style="page-break-after: always;"></h2>';
		
	$pdf->writeHTML($sm_str, true, false, true, false, '');

	//minus summary
	$smm_str='';
	$smm_str=$smm_str.minus_page_header($link,$bg,$bn,'Summary');
	$smm_str=$smm_str.'<table cellpadding="1" cellspacing="0" border="0.3" style="text-align:center;">';
	$smm_str=$smm_str.print_minus_tbl_hd();
	foreach($GLOBALS['smm'] as $rn=>$r)
	{
			$smm_str=$smm_str.'<tr>
				<td>Page:'.$rn.'</td>	
				<td>'.$GLOBALS['smm'][$rn]['rob'].'</td>	
				<td>'.$GLOBALS['smm'][$rn]['pt'].'</td>			
				<td>'.$GLOBALS['smm'][$rn]['sis_if'].'</td>			
				<td>'.$GLOBALS['smm'][$rn]['sis_sf'].'</td>			
				<td>'.($GLOBALS['smm'][$rn]['gpf']+$GLOBALS['smm'][$rn]['gpf_adv_recv']).'</td>			
				<td>'.$GLOBALS['smm'][$rn]['cpf'].'</td>			
				<td>'.$GLOBALS['smm'][$rn]['fes'].'</td>			
				<td>'.$GLOBALS['smm'][$rn]['food'].'</td>			
				<td>'.$GLOBALS['smm'][$rn]['car'].'</td>			
				<td>'.($GLOBALS['smm'][$rn]['hba_p']+$GLOBALS['smm'][$rn]['hba_i']).'</td>
				<td>'.($GLOBALS['smm'][$rn]['gpf4']+$GLOBALS['smm'][$rn]['gpf4_adv_recv']).'</td>
				<td>'.($GLOBALS['smm'][$rn]['recv_off']+$GLOBALS['smm'][$rn]['recv_est']).'</td>			
				<td>'.$GLOBALS['smm'][$rn]['tot_ded'].'</td>			
				<td>'.$GLOBALS['smm'][$rn]['tot_net'].'</td>	
				<td></td>					
							</tr>';
	}
	
	$smm_str=$smm_str.'<tr>
				<td>Total</td>	
				<td>'.	array_sum(array_column($GLOBALS['smm'],'rob'))	.'</td>	
				<td>'.	array_sum(array_column($GLOBALS['smm'],'pt'))	.'</td>	
				<td>'.	array_sum(array_column($GLOBALS['smm'],'sis_if'))	.'</td>	
				<td>'.	array_sum(array_column($GLOBALS['smm'],'sis_sf'))	.'</td>	
				<td>'.	(array_sum(array_column($GLOBALS['smm'],'gpf'))+
						array_sum(array_column($GLOBALS['smm'],'gpf_adv_recv')))	.'</td>	
				<td>'.	array_sum(array_column($GLOBALS['smm'],'cpf'))	.'</td>	
				<td>'.	array_sum(array_column($GLOBALS['smm'],'fes'))	.'</td>	
				<td>'.	array_sum(array_column($GLOBALS['smm'],'food'))	.'</td>	
				<td>'.	array_sum(array_column($GLOBALS['smm'],'car'))	.'</td>	
				<td>'.	(array_sum(array_column($GLOBALS['smm'],'hba_p'))+
										array_sum(array_column($GLOBALS['smm'],'hba_i')))	.'</td>	
				<td>'.	(array_sum(array_column($GLOBALS['smm'],'gpf4'))+
										array_sum(array_column($GLOBALS['smm'],'gpf4_adv_recv')))	.'</td>	
				<td>'.	(array_sum(array_column($GLOBALS['smm'],'recv_off'))+
										array_sum(array_column($GLOBALS['smm'],'recv_est')))	.'</td>	
				<td>'.	array_sum(array_column($GLOBALS['smm'],'tot_ded'))	.'</td>	
				<td>'.	array_sum(array_column($GLOBALS['smm'],'tot_net'))	.'</td>	
				<td></td>
						</tr>';
			
	$smm_str=$smm_str.'</table>';
	$smm_str=$smm_str.'<h2 style="page-break-after: always;"></h2>';
		
	$pdf->writeHTML($smm_str, true, false, true, false, '');
	
	}
	
	///////////manage front page///////////////
	$pdf->setPage(1);		//go back to page 1
	
	//Pay of Officer
	$poo=array_sum(array_column($GLOBALS['sm'],'gp_12'))+array_sum(array_column($GLOBALS['sm'],'pay_off'));
	//Pay of Establishment
	$poe=array_sum(array_column($GLOBALS['sm'],'gp_34'))+array_sum(array_column($GLOBALS['sm'],'pay_est'));
	
	$other_in_basic=array_sum(array_column($GLOBALS['sm'],'sppa'))
				+array_sum(array_column($GLOBALS['sm'],'fpa'))
				+array_sum(array_column($GLOBALS['sm'],'infection'));
	
	if($poo>0){$poo=$poo+$other_in_basic;}			
	else{$poe=$poe+$other_in_basic;}			
		
	//display pay of officer
	write_text($pdf,$poo,157,120,20,5);
	//display pay of establishment
	write_text($pdf,$poe,157,123,20,5);

	write_text($pdf,array_sum(array_column($GLOBALS['sm'],'ls')),157,126,20,5);

	write_text($pdf,array_sum(array_column($GLOBALS['sm'],'da')),157,130,20,5);
	write_text($pdf,array_sum(array_column($GLOBALS['sm'],'hra')),157,134,20,5);
	write_text($pdf,array_sum(array_column($GLOBALS['sm'],'cla')),157,139,20,5);
	//Interim releaf not implimented in inner and outer
	//write_text($pdf,$array_4['Interim_Relief_0112(+)'],164,145,20,5);
	write_text($pdf,array_sum(array_column($GLOBALS['sm'],'ta')),157,149,20,5);
	
	//Other allowances, original
	//$EDP0104_P=	array_sum(array_column($GLOBALS['sm'],'sppa'))
				//+array_sum(array_column($GLOBALS['sm'],'fpa'))
				//+array_sum(array_column($GLOBALS['sm'],'infection'))
				//+array_sum(array_column($GLOBALS['sm'],'ceil'))
				//+array_sum(array_column($GLOBALS['sm'],'ba'));	
				
	//As Akbarbhai asked sppa,fpa,infection will go into basic on front page
	$EDP0104_P=	array_sum(array_column($GLOBALS['sm'],'ceil'))
				+array_sum(array_column($GLOBALS['sm'],'ba'));	
									
	write_text($pdf,$EDP0104_P,157,153,20,5);
	
	write_text($pdf,array_sum(array_column($GLOBALS['sm'],'ma')),157,157,20,5);
	write_text($pdf,array_sum(array_column($GLOBALS['sm'],'wa')),157,162,20,5);
	write_text($pdf,array_sum(array_column($GLOBALS['sm'],'ua')),157,167,20,5);
	write_text($pdf,array_sum(array_column($GLOBALS['sm'],'na')),157,171,20,5);

	//write_text_fill_left($pdf,'0128 NPPA         0128(+)',112,184.5,55,4);
	//edited at jpg
	write_text($pdf,array_sum(array_column($GLOBALS['sm'],'npa')),157,178,20,5);
	write_text($pdf,array_sum(array_column($GLOBALS['sm'],'gross')),157,193,20,5);

	write_text($pdf,array_sum(array_column($GLOBALS['smm'],'fes')),157,198,20,5);
	$food_car=array_sum(array_column($GLOBALS['smm'],'food'))+array_sum(array_column($GLOBALS['smm'],'car'));
	write_text($pdf,$food_car,157,201,20,5);
	
	$EDP0101_M=array_sum(array_column($GLOBALS['smm'],'recv_off'))+
				array_sum(array_column($GLOBALS['smm'],'recv_est'));
										
	write_text($pdf,$EDP0101_M,157,205,20,5);

	$gross_minus_recovery=array_sum(array_column($GLOBALS['sm'],'gross'))
							-array_sum(array_column($GLOBALS['smm'],'fes'))
							-$food_car
							-$EDP0101_M;
							
	write_text($pdf,$gross_minus_recovery,157,215,20,5);
	
	//SMP ???
	//$tot_net_without_nongovt=array_sum(array_column($GLOBALS['smm'],'tot_net'))+
						//array_sum(array_column($GLOBALS['smm'],'gmcs_soc'))+
						//array_sum(array_column($GLOBALS['smm'],'lic'));				
	//write_text($pdf,$tot_net_without_nongovt,164,249,20,5);

	//total deduction calculation start here
	write_text($pdf,array_sum(array_column($GLOBALS['smm'],'itax')),236,42,20,5);
	
	$all_gpf4=array_sum(array_column($GLOBALS['smm'],'gpf4'))+
						array_sum(array_column($GLOBALS['smm'],'gpf4_adv_recv'));
	write_text($pdf,$all_gpf4,236,61,20,5);
	write_text($pdf,array_sum(array_column($GLOBALS['smm'],'cpf')),236,67,20,5);

	////write_text_fill_left($pdf,'GPF non Cl-IV   9670(-)',188,76,55,4);
	$all_gpf=array_sum(array_column($GLOBALS['smm'],'gpf'))+
						array_sum(array_column($GLOBALS['smm'],'gpf_adv_recv'));	
	write_text($pdf,$all_gpf,304,53,20,5);
	
	write_text($pdf,array_sum(array_column($GLOBALS['smm'],'rob')),236,92,20,5);
	write_text($pdf,array_sum(array_column($GLOBALS['smm'],'pt')),236,97,20,5);
	write_text($pdf,array_sum(array_column($GLOBALS['smm'],'sis_if')),236,108,20,5);
	write_text($pdf,array_sum(array_column($GLOBALS['smm'],'sis_sf')),236,118,20,5);
	
	$total_hba=	array_sum(array_column($GLOBALS['smm'],'hba_p'))+
				array_sum(array_column($GLOBALS['smm'],'hba_i'));	
	write_text($pdf,$total_hba,236,178,20,5);

	$total_a_deduction=array_sum(array_column($GLOBALS['smm'],'itax'))+
							$all_gpf4+
							array_sum(array_column($GLOBALS['smm'],'cpf'))+
							array_sum(array_column($GLOBALS['smm'],'rob'))+
							array_sum(array_column($GLOBALS['smm'],'pt'))+
							array_sum(array_column($GLOBALS['smm'],'sis_if'))+
							array_sum(array_column($GLOBALS['smm'],'sis_sf'))+
							$total_hba;
							
	$total_deduction=$total_a_deduction+$all_gpf;
	write_text($pdf,$total_a_deduction,236,198,20,5);
	write_text($pdf,$total_deduction,306,137,20,5);
	
	$net_total=$gross_minus_recovery-$total_deduction;
			
	$GLOBALS['net_for_back']=$net_total;		//for outer back
	write_text($pdf,$net_total,158,248,20,5);		//left

	write_text($pdf,$net_total,307,141.5,20,5);					//right
	
	//$net_total_words=Numbers_Words::toWords($net_total,"en_US");
	//write_text_small($pdf,$net_total_words.' only', 257,145.5,70,5);
	my_number_to_words($net_total,'yes');	
	write_text_small($pdf,$GLOBALS['n2s'].' only', 257,145.5,70,5);			

	////12170501
	if(round(($_POST['bill_group']/1000000),0)==12)
	{
		write_text_big_fill($pdf,'Gazetted',156,39,30,12);
	}
	elseif(round(($_POST['bill_group']/1000000),0)==34)
	{
		write_text_big_fill($pdf,'Non - Gazetted',150,35,30,12);
	}
		
	write_text($pdf,$_POST['bill_group'].'-'.$_POST['bill_number'],148,56,25,12);
	$bill_details=get_raw($link,'select * from bill_group where bill_group=\''.$bg.'\'');
	write_text_big_fill($pdf,$bill_details['remark'],70,73,35,10);
	

}



function write_text_fill_left($pdf,$text, $x,$y, $w,$h)
{
$pdf->SetFont('freesans','B',10);
$pdf->SetXY($x,$y);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$pdf->SetDrawColor(0);

$pdf->Rect($x, $y,$w,$h,'F');
$pdf->MultiCell($w, $h, $text , $border=1, $align='L', 
					$fill=true, $ln=1, $x='', $y='', $reseth=true, $stretch=0, 
					$ishtml=false, $autopadding=false, $maxh=0, $valign='T', $fitcell=false);	
}

function write_text_big_fill($pdf,$text, $x,$y, $w,$h)
{
$pdf->SetFont('courier','B',15);
$pdf->SetXY($x,$y);
$pdf->SetTextColor(0);
$pdf->SetFillColor(255);
$pdf->SetDrawColor(0);

$pdf->Rect($x, $y,$w,$h,'F');
$pdf->MultiCell($w, $h, $text , $border=1, $align='L', 
					$fill=true, $ln=1, $x='', $y='', $reseth=true, $stretch=0, 
					$ishtml=false, $autopadding=false, $maxh=0, $valign='T', $fitcell=false);
}


?>

