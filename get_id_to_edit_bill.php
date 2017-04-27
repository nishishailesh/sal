<?php
session_start();
require_once 'common.php';

//print_r($_POST);
echo '
<style>
	
table{
   border-collapse: collapse;
}

.border td , .border th{
    border: 1px solid gray;
}

.upload{
	background-color:lightpink;	
}

.noborder{
 border: none;
}


.hidedisable
{
	display:none;diabled:true
}

.section_header
{
	background-color:gray;
}
</style>';		



/////////////Main script start from here//////////////

$link=connect();

menu();

if(	isset($_POST['bill_group']) && 
	isset($_POST['staff_id']) &&
	isset($_POST['submit']))
	{
		if($_POST['submit']=='edit' ||$_POST['submit']=='refresh')
		{
			edit_salary($link,$_POST['staff_id'],$_POST['bill_group']);
		}
		elseif($_POST['submit']=='delete')
		{
			delete_raw_by_id_dpc($link,'salary','staff_id',$_POST['staff_id'],'bill_group',$_POST['bill_group']);
		}
		
	}
select_bill_group($link);
if(isset($_POST['bill_group'])){list_bill($link,$_POST['bill_group']);}

?>

