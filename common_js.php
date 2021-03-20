<?php

echo '<script>

function showhide_with_label(one,labell,textt) {
	//style="background-color:#5BC0DE;color:white;font-size:20px;border-radius: 8px;padding:10px;"
				if(document.getElementById(one).style.display == "none")
				{
					document.getElementById(one).style.display = "block";
					labell.style="background-color:#5BC0DE;font-size:20px;border-radius: 8px;padding:10px;";
					labell.innerHTML="Hide "+textt;
				}
				else
				{
					document.getElementById(one).style.display = "none";
					labell.innerHTML="Show "+textt;
				}

		}
function run_ajax(str,rid)
{
	//create object
	xhttp = new XMLHttpRequest();
	
	//4=request finished and response is ready
	//200=OK
	//when readyState status is changed, this function is called
	//responceText is HTML returned by the called-script
	//it is best to put text into an element
	xhttp.onreadystatechange = function() {
	  if (this.readyState == 4 && this.status == 200) {
		document.getElementById(rid).innerHTML = this.responseText;
	  }
	};

	//Setting FORM data
	xhttp.open("POST", "save_salary.php", true);
	
	//Something required ad header
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	// Submitting FORM
	xhttp.send(str);
	
	//used to debug script
	//alert("Used to check if script reach here");
}

function make_post_string(id,idd,t)
{
	k=encodeURIComponent(t.id);					//to encode almost everything
	v=encodeURIComponent(t.value);					//to encode almost everything
	post=\'field=\'+k+\'&value=\'+v+\'&staff_id=\'+id+\'&bill_group=\'+idd;
	return post;							
}

function do_work(id,idd,t)
{
	str=make_post_string(id,idd,t);
	//alert(post);
	run_ajax(str,\'response\');
}

function getfrom(one,two) {
			document.getElementById(two).value =one.value;
		}
	

function hide(one) {
				document.getElementById(one).style.display = "none";
		}



function showhide(one) {
	if(document.getElementById(one).style.display == "none")
	{
		document.getElementById(one).style.display = "block";
	}
	else
	{
		document.getElementById(one).style.display = "none";
	}
}

function read_bn()
{
	xx=prompt(\'Copy to bill number:\');
	
}
</script>
<script type="text/javascript" src="date/datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="date/datepicker.css" /> ';


echo '

<style>
	
table{
   border-collapse: collapse;
}

.border td , .border th{
    border: 1px solid black;
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

</style>


';

?>
