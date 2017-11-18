<?php

echo '

<style>
	.menu {border:0px;border-spacing: 0;border-collapse: collapse;background-color:lightgreen;}
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

function menu()
{	
		
echo '
<form method=post>
<table align=center class=\"menu\">
			
</td><td>
		<button type=button onclick="showhidemenu(\'button5\')">Bill</button>
		<table  id="button5" class="menu" style="position:absolute; display:none;">
			<tr><td>
				<button formaction=new_bill.php type=submit onclick="hidemenu()" name=action value=new_bill_1>New</button>
			</td></tr>	
			<tr><td>
				<button formaction=copy_bill.php type=submit onclick="hidemenu()" name=action value=copy_bill>Copy</button>
			</td></tr>
			<tr><td>
				<button formaction=edit_bill.php type=submit onclick="hidemenu()" name=action value=edit_bill>Edit</button>
			</td></tr>
			<tr><td>
				<button formaction=edit_bill_calculate.php type=submit onclick="hidemenu()" name=action value=edit_bill>Edit(Calculate)</button>
			</td></tr>			
			<tr><td>
				<button formaction=print_bill.php type=submit onclick="hidemenu()" 
				name=action value=print_bill>Print</button>
			</td></tr>
			<tr><td>
				<button formaction=copy_bill_zero.php type=submit onclick="hidemenu()" name=action value=copy_bill>Copy(0)</button>
			</td></tr>
			<tr><td>
				<button formaction=salary_slip.php formtarget=_blank type=submit onclick="hidemenu()" name=action value=print_salary>Salary Slip</button>
			</td></tr>			
		</table>
</td><td>
		<button type=button onclick="showhidemenu(\'button6\')">Salary</button>
		<table  id="button6" class="menu" style="position:absolute; display:none;">
			<tr><td>
				<button formaction=new_salary.php type=submit onclick="hidemenu()" name=action value=new_salary_1>New</button>
			</td></tr>		
			<tr><td>
				<button formaction=get_id_for_edit_salary.php type=submit onclick="hidemenu()" name=edit>Edit</button>
			</td></tr>
			<tr><td>
				<button formaction=get_id_for_annual_salary.php type=submit onclick="hidemenu()" name=annual>Annual salary</button>
			</td></tr>
		</table>
</td><td>
                <button type=button onclick="showhidemenu(\'button8\')">Staff</button>
                <table  id="button8" class="menu" style="position:absolute; display:none;">
                        <tr><td>
                                <button formaction=staff_management.php type=submit onclick="hidemenu()" name=action value=staff_management>Add/Edit/Remove</button>
                        </td></tr>
                        <tr><td>
                                <button formaction=staff_position_management.php type=submit onclick="hidemenu()" name=action value=staff_management>Senctioned Post</button>
                        </td></tr>
                </table>
</td><td>
                <button type=button onclick="showhidemenu(\'button7\')">Advance</button>
                <table  id="button7" class="menu" style="position:absolute; display:none;">
                        <tr><td>
                                <button formaction=edit_hba.php type=submit onclick="hidemenu()" name=action value=edit_hba>HBA</button>
                        </td></tr>
                </table>

</td><td>
		<button  type=button onclick="showhidemenu(\'button3\')">Manage My Account('.$_SESSION['login'].')</button>
		<table  id="button3" class="menu" style="position: absolute;display:none;">
		<tr><td>
			<button formaction=logout.php type=submit onclick="hidemenu()" name=new>Logout</button>
		</td></tr>
		<tr><td>
			<button formaction=change_pass.php type=submit onclick="hidemenu()" name=new>Change Password</button>
		</td></tr>
		</table>	
</td></tr>
</table>
</form>
';

}


?>
