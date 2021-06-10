<?php

echo '

<style>
	.menu {border:0px;border-spacing: 0;border-collapse: collapse;background-color:lightblue;}
</style>

<script type="text/javascript" >
		function showhidemenu(one) 
		{		
			xx=document.getElementsByClassName(\'menu\');			
			for(var i = 0; i < xx.length; i++)
			{
				if(xx[i]!=document.getElementById(one))
				{
					xx[i].style.display = "none";		
				}
				
				else if(xx[i]==document.getElementById(one))
				{
					if(xx[i].style.display == "block")
					{
						xx[i].style.display = "none";
					}
					else
					{
						xx[i].style.display = "block";
					}		
				}
			}	
		}
		
		function hidemenu() {
		
			xx=document.getElementsByClassName(\'menu\');
			for(var i = 0; i < xx.length; i++)
			{
				xx[i].style.display = "none";		
			}
		}
		
		//document.onclick=function(){hidemenu();};
		</script>';
function menu($link)
{		
echo '
	<div class="container-fluid">
          <h2 class="text-danger text-center  bg-dark">Salary Management</h2>
		<div class="row">				
			<div class="col-sm-12 bg-light text-center">	
      <form method=post>
      <table align=center class=\"menu\">
			
</td><td>
		<button class="btn btn-primary btn-lg" type=button onclick="showhidemenu(\'button5\')">Bill</button>
		<table  id="button5" class="menu" style="position:absolute; display:none;">
			<tr><td>
				<button  class="btn btn-primary btn-block" formaction=new_bill.php type=submit onclick="hidemenu()" name=action value=new_bill_1>New</button>
			</td></tr>	
			<tr><td>
				<button class="btn btn-primary btn-block" formaction=copy_bill.php type=submit onclick="hidemenu()" name=action value=copy_bill>Copy</button>
			</td></tr>
			<tr><td><div class=mx-auto>
				<button class="btn btn-primary btn-block" formaction=edit_bill.php type=submit onclick="hidemenu()" name=action value=edit_bill>Edit</button>
			</td></tr></div>
			<tr><td>
				<button class="btn btn-primary btn-block" formaction=edit_bill_calculate.php type=submit onclick="hidemenu()" name=action value=edit_bill>Edit(Calculate)</button>
			</td></tr>		
                        <tr><td>
                                <button class="btn btn-primary btn-block" formaction=edit_bill_calculate1.php type=submit onclick="hidemenu()" name=action value=edit_bill>Edit(Calculate_1)</button>
                        </td></tr> 
                        <tr><td>
                                <button class="btn btn-primary btn-block" formaction=edit_bill_calculate2.php type=submit onclick="hidemenu()" name=action value=edit_bill>Edit(Calculate_2)</button>
                        </td></tr> 

			<tr><td>
				<button class="btn btn-primary btn-block" formaction=print_bill.php type=submit onclick="hidemenu()" name=action value=print_bill>Print</button>
			</td></tr>
			<tr><td>
				<button class="btn btn-primary btn-block"  formaction=copy_bill_zero.php type=submit onclick="hidemenu()" name=action value=copy_bill>Copy(0)</button>
			</td></tr>
                        <tr><td>
				<button class="btn btn-primary btn-block"  formaction=copy_bill_zero_selective.php type=submit onclick="hidemenu()" name=action value=copy_bill>Copy selective(0)</button>
			</td></tr>
			<tr><td>
				<button class="btn btn-primary btn-block"  formaction=salary_slip_pdf.php  type=submit onclick="hidemenu()" name=action value=print_salary>Salary Slip</button>
			</td></tr>			
		</table>
</td><td>
		<button class="btn btn-primary btn-lg"  type=button onclick="showhidemenu(\'button6\')">Salary</button>
		<table  id="button6" class="menu" style="position:absolute; display:none;">
			<tr><td>
				<button class="btn btn-primary btn-block"  formaction=new_salary.php type=submit onclick="hidemenu()" name=action value=new_salary_1>New</button>
			</td></tr>		
			<tr><td>
				<button class="btn btn-primary btn-block" formaction=get_id_for_edit_salary.php type=submit onclick="hidemenu()" name=edit>Edit</button>
			</td></tr>
			<tr><td>
				<button class="btn btn-primary btn-block"  formaction=get_id_for_annual_salary.php type=submit onclick="hidemenu()" name=annual>Search salary</button>
			</td></tr>
			<tr><td>
				<button class="btn btn-primary btn-block"  formaction=get_id_for_annual_salary_pex.php type=submit onclick="hidemenu()" name=annual>Print Export Annual Salary</button>
			</td></tr>

			<tr><td>
				<button class="btn btn-primary btn-block"  formaction=get_id_for_annual_salary_pex_v.php type=submit onclick="hidemenu()" name=annual>Print Export Annual Salary(V)</button>
			</td></tr>

			<tr><td>
				<button class="btn btn-primary btn-block"  formaction=get_id_for_annual_salary_pex_v_pdf.php type=submit onclick="hidemenu()" name=annual>Print Export Annual Salary(V)(PDF)</button>
			</td></tr>
			
			<tr><td>
				<button class="btn btn-primary btn-block"  formaction=get_id_for_annual_all_salary.php type=submit onclick="hidemenu()" name=annual>All salary</button>
			</td></tr>
		</table>
</td><td>
                <button class="btn btn-primary btn-lg " type=button onclick="showhidemenu(\'button8\')">Staff</button>
                <table  id="button8" class="menu" style="position:absolute; display:none;">
                        <tr><td>
                                <button class="btn btn-primary btn-block" formaction=staff_management.php type=submit onclick="hidemenu()" name=action value=staff_management>Add/Edit/Remove</button>
                        </td></tr>
                        <tr><td>
                                <button class="btn btn-primary btn-block"  formaction=staff_position_management.php type=submit onclick="hidemenu()" name=action value=staff_management>Senctioned Post</button>
                        </td></tr>
                </table>
</td><td>
                <button class="btn btn-primary btn-lg" type=button onclick="showhidemenu(\'button7\')">Advance</button>
                <table  id="button7" class="menu" style="position:absolute; display:none;">
                        <tr><td>
                                <button class="btn btn-primary"  formaction=edit_hba.php type=submit onclick="hidemenu()" name=action value=edit_hba>HBA</button>
                        </td></tr>
                </table>

</td><td>
		<button class="btn btn-primary btn-lg"  type=button onclick="showhidemenu(\'button3\')">Manage My Account('.$_SESSION['login'].')</button>
		<table  id="button3" class="menu" style="position: absolute;display:none;">
		<tr><td>
			<button class="btn btn-primary btn-block"  formaction=logout.php type=submit onclick="hidemenu()" name=logout>Logout</button>
		</td></tr>
		<tr><td>
			<button class="btn btn-primary btn-block"  formaction=change_pass.php type=submit onclick="hidemenu()" name=change_pwd>Change Password</button>
		</td></tr>
		</table>	
</td><td>
      <button class="btn btn-primary btn-lg" type=submit name=action value=help formaction=help.php>Help</button>
</td><td>
              <td><b> <span style="background-color:white;color:blue;"> Password Expires On  [';echo expirydate($link,'c34',$_SESSION['login']); echo']  </span></b></td>
</td></tr>
</table>
</form>
</div>
</div>
</div>';

}


?>
