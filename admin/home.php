<?php
include "include/admin_header.php";
include_once "redirect_to_adminlogin.php";
global $dbc;
?>
<table width="98%" height="350" cellpadding="0"  cellspacing="0">
  <tr>
    <td colspan="3">
	  <table width="100%"  cellspacing="0" cellpadding="0">
         <tr>
          	<td class="body_head1">&nbsp; </td>
          	<td align="center" class="body_bg1">Welcome To The Admin Area&nbsp;</td>
          	<td class="body_head2" align="right">&nbsp;</td>
        </tr>
     </table>
	 </td>
  </tr>
  <tr>
    <td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
	<td width="100%" height="100%" align="center" valign="middle">
		<table width="30%" border="1" cellpadding="4" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
			<tr class="bgc"> 
				<td width="70%" height="30" align="left" valign="middle" class="label_name1">Number of Admin Users : </td>
				<td width="30%" height="30" align="center" valign="middle">
					<a href="admin_listing.php" class="link2"><?php echo $dbc->getNumRows("select * from cpefs_admin",__LINE__,__FILE__);?>  </a>
				</td>
			</tr>
			<tr class="bgc2">
				<td width="70%" height="30" align="left" valign="middle" class="label_name1">Number of Units : </td>
				<td width="30%"  height="30" align="center" valign="middle">
					<a href="unit_listing.php" class="link2"><?php echo $dbc->getNumRows("select * from cpefs_units where unit_status = 1",__LINE__,__FILE__);?></a>
				</td>
			</tr >
			<tr class="bgc"> 
				<td width="70%"  height="30" align="left" valign="middle" class="label_name1">Number of Members : </td>
				<td width="30%" height="30" align="center" valign="middle">
					<a href="member_listing.php" class="link2"> <?php  echo $dbc->getNumRows("select * from cpefs_members where member_status = 2 ",__LINE__,__FILE__);?> </a>
				</td>
			</tr>
		</table>
<?php include "include/admin_footer.php"; ?>
