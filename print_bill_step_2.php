<?php
session_start();
require_once 'common.php';

//echo '<pre>';print_r($_POST);echo '</pre>';


$link=connect();
head();
menu($link);

echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
      <table class="table table-striped">';
get_bill_number($link,$_POST['bill_group']);
echo '<br>';
echo '</td></tr><tr><td colspan=2>';
echo '<div class="container" >
		     <div class="row">
		     <div class="col-*-6 mx-auto">
	      <table  class="table table-striped">';
echo '<tr><th colspan=2  style="background-color:lightblue;text-align: center;">Your Current Selection is As follows</th></tr>';
echo '<form method=post>';
echo '<tr><td>Bill Group Selected:</td><td><input type=text readonly name=bill_group value=\''.$_POST['bill_group'].'\'></td></tr>';
echo '<tr><td>Bill Number Selected:</td><td><input type=text readonly name=bill_number value=\''.$_POST['bill_number'].'\'></td></tr>';
echo '<tr><th colspan=2 style="background-color:lightblue;text-align: center;">Following reports are available for print</th></tr>';



    
echo '<tr><td>Outer</td><td>
		Outer Front:<select name=outer_front><option>general</option><option>rome</option><option>blindness</option><option>blank</option></select><br>
		Outer Back:<select name=outer_back><option>general</option><option>special</option><option>ltc</option><option>blank</option></select><br>
		<button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=print_outer formaction=outer.php>Print Outer</button>
</td></tr>';


echo '<tr><td>Outer(ROME)</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=print_outer formaction=outer_rome.php>Print Outer(ROME)</button></td></tr>';
echo '<tr><td>Outer(BLIND)</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=print_outer formaction=outer_blind.php>Print Outer(BLIND)</button></td></tr>';
echo '<tr><td>Outer(Special)</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=print_outer_special formaction=outer_special_1.php>Print Outer(Special)</button></td></tr>';
echo '<tr><td>Outer(LTC)</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=print_outer_special formaction=outer_special_ltc.php>Print Outer(LTC)</button></td></tr>';
echo '<tr><td>Outer(HLT-70)</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=print_outer_special formaction=outer_special_hlt_70.php>Print Outer(HLT-70)</button></td></tr>';
echo '<tr><td>Income Tax</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=itax formaction=itax.php>Print ITax</button></td></tr>';
echo '<tr><td>HR</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=house_rent formaction=house_rent.php>Print HRent</button></td></tr>';
echo '<tr><td>PT</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=ptax formaction=ptax.php>Print PTax</button></td></tr>';

//echo '<tr><td>GPF(non Class IV)</td><td><button type=submit formtarget=_blank name=action value=gpf formaction=gpf.php>Print GPF (non Class IV)</button></td></tr>';
echo '<tr><td>GPF(non Class IV)(MED)</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=gpf_med formaction=gpf_med.php>Print GPF (non Class IV)(MED)</button></td></tr>';
echo '<tr><td>GPF(non Class IV)(PH)</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=gpf_ph formaction=gpf_ph.php>Print GPF (non Class IV)(PH)</button></td></tr>';
echo '<tr><td>GPF(non Class IV)(PW)</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=gpf_pw formaction=gpf_pw.php>Print GPF (non Class IV)(PW)</button></td></tr>';

echo '<tr><td>GPF(Class IV)</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=gpf formaction=gpf_4.php>Print GPF (class IV)</button></td></tr>';
echo '<tr><td>CPF</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=gpf_med formaction=cpf_med.php>Print CPF</button></td></tr>';

echo '<tr><td>SIS</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=sis formaction=sis.php>Print SIS</button></td></tr>';
echo '<tr><td>SIS-C</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=sis_c formaction=sis_c.php>Print SIS Annexure -C</button></td></tr>';
echo '<tr><td>Misc. Recovery</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=recovery formaction=recovery.php>Print Recovery</button></td></tr>';
echo '<tr><td>Bank and Net</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=bank_net formaction=bank_net.php>Print Bank Net</button></td></tr>';
echo '<tr><td>Festival Advance</td><td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=festival_advance formaction=festival_advance.php>Print Festival advance</button></td></tr>';
echo '<tr><td>HBA Interest Recovery</td>
			<td><button class="btn btn-sm btn-primary" type=submit formtarget=_blank name=action value=HBA_int_recv formaction=hba_int_recv.php>
					Print HBA interest recovery</button></td></tr>';
echo '<tr><td>HBA Principle Recovery</td>
			<td><button  class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=HBA_pri_recv formaction=hba_pri_recv.php>
					Print HBA Principle recovery</button></td></tr>';
echo '<tr><td>Non Govt Deduction</td>
			<td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=non_govt_ded formaction=non_govt.php>
					Print Non Government Deduction</button></td></tr>';
echo '<tr><td>Non Govt Deduction-LIC</td>
			<td><button  class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=non_govt_ded_lic formaction=non_govt_lic.php>
					Print Non Government Deduction(LIC)</button></td></tr>';
echo '<tr><td>Non Govt Deduction-SOCIETY</td>
			<td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=non_govt_ded_soc formaction=non_govt_soc.php>
					Print Non Government Deduction(Society)</button></td></tr>';
echo '<tr><td>Check Summary</td>
			<td><button class="btn btn-sm btn-primary" type=submit formtarget=_blank name=action value=check_summary formaction=check_summary.php>
					Print Check Summary</button></td></tr>';
echo '<tr><td>Staff Summary</td>
			<td><button class="btn btn-sm btn-primary"  type=submit formtarget=_blank name=action value=staff_summary formaction=staff_position_report.php>
					Print Staff summary</button></td></tr>';
echo '</form>';
echo '</td></tr></table></div></div></div>';

echo '</td></tr></table></div></div></div>';
htmltail();
?>

