<?php
include "include/admin_header.php";
include_once "redirect_to_adminlogin.php";
global $dbc;

$op=0;
if(isset($_POST['submit']))
{

			 $qry ="records_display_admin=?";
			 $qry .=",admin_receive_email=?";
			 $qry .=",admin_send_email=?";
			 $qry .=",booking_reminder_email_months=?";

			 $dbc->update("update cpefs_settings set $qry where id=1",__LINE__,__FILE__,array("issi",&$_POST['records_display_admin'],&$_POST['admin_receive_email'],&$_POST['admin_send_email'],&$_POST['booking_reminder_email_months']));
			 $op=2;
}
else
{
	$rows=$dbc->getSingleRow("SELECT * from cpefs_settings",__LINE__,__FILE__);
	$_POST["records_display_admin"]=$rows["records_display_admin"];
	$_POST["admin_receive_email"]=$rows["admin_receive_email"];
	$_POST["admin_send_email"]=$rows["admin_send_email"];
	$_POST["booking_reminder_email_months"]=$rows["booking_reminder_email_months"];
}
?>
<table width="98%" height="350" cellpadding="0"  cellspacing="0">
	<tr>
		<td colspan="3">
			<table width="100%"  cellspacing="0" cellpadding="0">
				<tr>
					<td class="body_head1">&nbsp;</td>
					<td align="center" class="body_bg1"><strong>Manage Settings</strong></td>
					<td class="body_head2">&nbsp;</td>
				</tr>
			</table>
		</td>
	 </tr>
 <tr>
    <td width="1" bgcolor="#FFA3AB">
		<img src="images/bg.gif" width="1" height="1" alt="">
	</td>
    <td width="100%" height="360" align="center" valign="top">
		  <table width="60%">
				<tr>
					<td width="100%"  align="right" valign="top" class="error">
					  <?php
					  if($op=='2')
							echo "Settings Successfully Updated";
					  ?>
					</td>
			  </tr>
		  </table>
		  <form action="" method="post" name="frm" id="frm">
		  <table width="65%" border="1" cellpadding="0" cellspacing="0" style="border-collapse :collapse" align="center">
      		<tr>
				<td>
					 <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse :collapse" align="center">
						<tr>
								<td width="75%" class="heading" align="center" >Settings</td>
								<td class="heading" align="center" >Value </td>
						</tr>
						<tr class="bgc">
							<td align="left" style="padding-left:16px;">No of Records Displayed in Admin Listing Pages</td>
							<td align="left">
						  <input type="text"  class="inptbox"  size="5" name="records_display_admin" value="<?php echo $_POST["records_display_admin"]?>">						  </td>
						</tr>
						<tr class="bgc2">
							<td align="left" style="padding-left:16px;">Admin Receive Email Address</td>
							<td align="center">
						  <input type="text"  class="inptbox"  size="45" name="admin_receive_email" value="<?php echo $_POST["admin_receive_email"]?>">						  </td>
						</tr>
						<tr class="bgc">
							<td align="left" style="padding-left:16px;">Admin Send Email Address</td>
							<td align="center">
						  <input type="text"  class="inptbox"  size="45" name="admin_send_email" value="<?php echo $_POST["admin_send_email"]?>">						  </td>
						</tr>
						<tr class="bgc">
							<td align="left" style="padding-left:16px;">Booking Reminder Email Months</td>
							<td align="left">
						  <input type="text"  class="inptbox"  size="2" name="booking_reminder_email_months" value="<?php echo $_POST["booking_reminder_email_months"]?>">						  </td>
						</tr>
				</table>
	  		</td>
	  	 </tr>
	 </table><br />
	 <table width="100%">
		<tr align="center">
			<td width="55%" height="20" align="right" valign="bottom">
				<?php
				if($_SESSION['IS_ADMIN']==1)
					echo '<input name="submit" type="submit" class="button1" value="Submit" />';
				?>
			</td>
			<td width="45%" height="20" colspan="3" align="right" valign="bottom" class="link1">
			</td>
		</tr>
		<tr align="center">
			<td height="20" align="center" valign="bottom" colspan="4">
				<a href="backup_database.php" class="link2">[Download Database Backup]</a>
			</td>
		</tr>
	  </table>
	</form>
<?php include "include/admin_footer.php";?>
