<style>
.btnmenu{
	background-color:blue;
	color:white;
 }
 .btnedit{
	  background-color:#eab72a;
	  color:black;
	  margin:4px;
	  padding:2px 5px 2px 5px;
}

</style>
<?php
session_start();
require_once 'common.php';
require_once 'common_js.php';

//////////////
$link=connect();
head();
menu($link);

?>

<div>
 <span class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;padding:10px;" id=bill_menu_btn
  onclick="showhide_with_label('bill_menu_help',this,'Bill Menu Help')">Show Bill help</span>
  <input type=hidden name=id value=>
    <br><br>
     <div id=bill_menu_help style="border-style: solid;padding:10px;background-color:#f2f2f2;display:none;">
       <div>
        
          <ol>  
             <h5><li> Click <span class="btnmenu">New</span> buttons to create new bill group.</li></h5>           
             <h5><li> Click <span class="btnmenu">Copy</span> buttons to copy old bill to new bill.</li></h5>           
             <h5><li> Click <span class="btnmenu">Edit</span> buttons and select bill group after click <span class="btnedit">Edit</span> button to edit bill details.</li></h5> 
                       <ol>
						   <h5><li> Click <span style="background-color:blue;color:white;margin:2px;padding:2px 5px 2px 5px;">P</span> buttons to Print a single record.   </li></h5> 
						   <h5><li> Click <span class="btnedit">E</span> buttons to Edit a single record.   </li></h5> 
						   <h5><li> Click <span style="background-color:#3ec2c4;color:white;margin:2px;padding:2px 5px 2px 5px;">C</span> buttons to Copy a single record.   </li></h5> 
						   <h5><li> Click <span style="background-color:red;color:white;margin:2px;padding:2px 5px 2px 5px;">D</span> buttons to Delete a single record.   </li></h5> 
                       </ol>
             <h5><li> Click <span class="btnmenu">Edit [Calculate]</span> buttons  and select bill group after click <span class="btnedit">Edit</span> button to edit bill details.</li></h5>            
                       <ol>
						   <h5><li> Click <span style="background-color:blue;color:white;margin:2px;padding:2px 5px 2px 5px;">P</span> buttons to Print a single record.   </li></h5> 
						   <h5><li> Click <span class="btnedit">E</span> buttons to Edit a single record.   </li></h5> 
						   <h5><li> Click <span style="background-color:#3ec2c4;color:white;margin:2px;padding:2px 5px 2px 5px;">C</span> buttons to Copy a single record.   </li></h5> 
						   <h5><li> Click <span style="background-color:red;color:white;margin:2px;padding:2px 5px 2px 5px;">D</span> buttons to Delete a single record.   </li></h5> 
                       </ol>
             <h5><li> Click <span class="btnmenu">Print</span> buttons and select bill group and after select bill number click <span style="background-color:green;color:white;margin:4px;padding:2px 5px 2px 5px;">print_reports</span>button to display all reports are available for print .</li></h5>       
             <h5><li><span class="btnmenu">Copy[0]</span> buttons to copy old bill to new bill.</li></h5>           
             <h5><li> Click <span class="btnmenu">Salary Slip</span> buttons and select bill group and select or write page par print after click  <span style="background-color:green;color:white;margin:4px;padding:2px 5px 2px 5px;">select_bill_group</span>button to display all print buttons. </li></h5>                       
          </ol>
          
       </div>
  </div>
</div>

<span class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;padding:10px;" id=salary_menu_btn
 onclick="showhide_with_label('salary_menu_help',this,'Salary Menu Help')">Show Salary help</span>
  <input type=hidden name=id value=>
    <br><br>
     <div id=salary_menu_help style="border-style: solid;padding:10px;background-color:#f2f2f2;display:none;">
       <div>
           <ol> 
			   <h5><li> Click <span class="btnmenu">New</span> buttons to select bill group after select staff click  <span style="background-color:green;color:white;margin:4px;padding:2px 5px 2px 5px;">select_staff_id</span>button to diplay all information you can edit and enter new salary information.</li></h5>           
               <h5><li> Click <span class="btnmenu">Edit</span> buttons to Choose Staff for whose salary is to be edited.</li></h5>   
						 <ol>
						   <h5><li> Click <span class="btnedit">E</span> buttons to Edit a single record.   </li></h5> 
						   <h5><li> Click <span style="background-color:#3ec2c4;color:white;margin:2px;padding:2px 5px 2px 5px;">C</span> buttons to Copy a single record.   </li></h5> 
						   <h5><li> Click <span style="background-color:red;color:white;margin:2px;padding:2px 5px 2px 5px;">D</span> buttons to Delete a single record.   </li></h5> 
                          </ol>
               <h5><li> Click <span class="btnmenu">Annual Salary</span> buttons to Choose Staff for whose annual salary is to be viewed and write year after click<span style="background-color:green;color:white;margin:4px;padding:2px 2px 2px 5px;">annual_salary</span>button to diplay year wise salary.</li></h5>           
               <h5><li> Click <span class="btnmenu">All Annual Salary</span> buttons to Choose Staff for whose annual salary is to be viewed click <span style="background-color:green;color:white;margin:2px;padding:2px 5px 2px 5px;">annual_salary</span>button to display all salary.</li></h5>   
                          
          </ol>    
       </div>
  </div>
</div>
<span class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;padding:10px;" id=staff_menu_btn
onclick="showhide_with_label('staff_menu_help',this,'Staff Menu Help')">Show Advance Help</span>
  <input type=hidden name=id value=>
    <br><br>
     <div id=staff_menu_help style="border-style: solid;padding:10px;background-color:#f2f2f2;display:none;">
       <div>
		    <ol> 
			   <h4><li> Click <span class="btnmenu">Add/Edit/Remove</span> buttons to  diplay all record.</li></h4>           
						 <ol>
						  <h5><li> Click <span style="background-color:green;color:white;margin:4px;padding:2px 5px 2px 5px;">S</span> buttons to Save a single record.   </li></h5> 
						   <h5><li> Click <span style="background-color:red;color:white;margin:2px;padding:2px 5px 2px 5px;">D</span> buttons to Delete a single record.   </li></h5> 
                          </ol>
               <h4><li> Click <span class="btnmenu">Senctioned Post</span> buttons to add,edit and delete record.<span style="background-color:green;color:white;margin:4px;padding:2px 2px 2px 5px;">annual_salary</span>button to diplay year wise salary.</li></h5>              
                         <ol>
						  <h5><li> Click <span style="background-color:green;color:white;margin:4px;padding:2px 5px 2px 5px;">S</span> buttons to Save a single record.   </li></h5> 
						   <h5><li> Click <span style="background-color:red;color:white;margin:2px;padding:2px 5px 2px 5px;">D</span> buttons to Delete a single record.   </li></h5> 
                          </ol> 
          </ol>
       </div>
  </div>
</div>
<span class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;padding:10px;" id=advance_menu_btn
onclick="showhide_with_label('advance_menu_help',this,'Advance Menu Help')">Show Staff help</span>
  <input type=hidden name=id value=>
    <br><br>
     <div id=advance_menu_help style="border-style: solid;padding:10px;background-color:#f2f2f2;display:none;">
       <div>
           <ol> 
			   <h5><li> Click <span class="btnmenu">HBA</span> buttons to  diplay all record.</li></h5>           
						 <ol>
						  <h5><li> Click <span style="background-color:green;color:white;margin:4px;padding:2px 5px 2px 5px;">S</span> buttons to Save a single record.   </li></h5> 
						   <h5><li> Click <span style="background-color:red;color:white;margin:2px;padding:2px 5px 2px 5px;">D</span> buttons to Delete a single record.   </li></h5> 
						   <h5><li> Click <span style="background-color:#3ec2c4;color:white;margin:2px;padding:2px 5px 2px 5px;">V</span> buttons to view a single record.   </li></h5> 
                          </ol
          </ol>
       </div>
  </div>
</div>
<span class="menub" style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;padding:10px;" id=managemy_account_menu_btn 
onclick="showhide_with_label('managemy_account_menu_help',this,'Managemy Account Menu help')">Show Staff help</span>
  <input type=hidden name=id value=>
    <br><br>
     <div id=managemy_account_menu_help style="border-style: solid;padding:10px;background-color:#f2f2f2;display:none;">
       <div>
            <ol> 
			   <h5><li> Click <span class="btnmenu">Logout</span> buttons to logout the salary management system.</li></h5>           
			   <h5><li> Click <span class="btnmenu">change password</span> buttons to change password for access to staff database.</li></h5>  		 
               
          </ol>
         
         
       </div>
  </div>
</div>

 <!--managemy account-->
  <div>
	</div>
       <div>
         
       </div>
 </div>
 
<?php

htmltail();

?>
 
