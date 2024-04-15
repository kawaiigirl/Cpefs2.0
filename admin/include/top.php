<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once "../inc/common.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Administration:: <?php echo SITE_TITLE?> </title>
 <link href="style.css" rel="stylesheet" type="text/css">
<script type="javascript">
function status_chk_all()
{
	if(frm.status_all.checked)
	{
        let i=frm.elements.length;
        let j;
		for(j=0;j<i;j++)
		{
			if(document.frm.elements[j].name==='status[]')
			{			
				document.frm.elements[j].checked=true;
			}
		}
	}
	else
	{
        let i=frm.elements.length;
        let j;
		for(j=0;j<i;j++)
		{
			if(document.frm.elements[j].name==='status[]')
			{			
				document.frm.elements[j].checked=false;
			}
		}
	}
}

function delete_chk_all()
{
	if(frm.delete_all.checked)
	{
        let i=frm.elements.length;
        let j;
		for(j=0;j<i;j++)
		{
			if(document.frm.elements[j].name==='delete[]')
			{			
				document.frm.elements[j].checked=true;
			}
		}
	}
	else
	{
        let i=frm.elements.length;
        let j;
		for(j=0;j<i;j++)
		{
			if(document.frm.elements[j].name==='delete[]')
			{			
				document.frm.elements[j].checked=false;
			}
		}
	}
}

</script>

<script type="javascript">
function charactersOnly(e)
{
    let unicode=e.charCode? e.charCode : e.keyCode
		if ((unicode>96 && unicode<123) || unicode===8 || (unicode >48 && unicode<57) || unicode===37 || unicode===39  )//if  numbers are between [A-Z] or [a-z]character
		return true;
		else
		 {
		 alert("Invalid Character");
		  //disable key press
		 return false;
		 }

}

function four_selection(name,no)
{
    let k=0;
    let i=document.frm.elements.length;
    let j;
		for(j=0;j<i;j++)
		{
			if(document.frm.elements[j].name===name)
			{			
				if(document.frm.elements[j].checked===true)
				k++;
			}
		}
		
	   if(k >no)
	   {
   		alert("You have already  Selected  "+no);
		return k;
	   }

}

function same_selection_check(name1,name2)
{
    let l=document.frm.elements[name1].length;
    let f=0;
 
 	 for(let i=0;i<l;i++)
		if((document.frm.elements[name1][i].checked===true)&& (document.frm.elements[name2][i].checked===true))
		{
 			alert('This Category All ready Selected For Another Use');
			f=1;
		}
	return f;
}


function code_generation(name,code)
{
    let k=[];
    let t=document.getElementById(name).value;
    let len=t.length;
   for(let i=0;i<len+1;i++)
     {
         let asccode=t.charCodeAt(i);
		if(asccode>64 && asccode<91 || asccode>96 && asccode<123 || (asccode >47 && asccode<58))
		{
		   	k=k+t.substr(i,1);
		}
	 }
   document.getElementById(code).value=k.toLowerCase();
   return true;
}

function title_generation(name1,name2)
{
  document.getElementById(name2).value=document.getElementById(name1).value;
}




</script>


<?php include "include/menu_head.php"; ?>
</head>
<body>