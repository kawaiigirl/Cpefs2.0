<?php
include "include/admin_header.php";
include_once "redirect_to_adminlogin.php";
$msg = "";
global $database;
if(isset($_POST['submit']))
{
    $sub = "";
	if($_POST["fdate"]!="")
		$sub  = " And check_in_date>='$_POST[fdate]'";
	if($_POST["tdate"]!="")
		$sub .= " And check_in_date<='$_POST[tdate]'";
	$_SESSION["QUERY"] = "Select cpefs_booking.*,cpefs_members.member_name,cpefs_units.unit_name 
						  From cpefs_booking 
						  Inner Join cpefs_members On cpefs_members.member_id=cpefs_booking.member_id 
						  Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id 
						  Where 1 $sub";
	$msg ='<input type="button" class="button1" value="Download CSV" onclick="window.location.href=\'export_bookings.php\'" />';
}
?>
<script language="javascript" src="include/popcalendar.js"></script>
<table width="98%" height="350" cellpadding="0"  cellspacing="0">
	<tr>
		<td colspan="3">
			<table width="100%"  cellspacing="0" cellpadding="0">
				<tr>
					<td class="body_head1">&nbsp;</td>
					<td align="center" class="body_bg1"><strong>Export Booking Information</strong></td>
					<td class="body_head2">&nbsp;</td>
				</tr>
			</table>
		</td>
	 </tr>
 <tr>
    <td width="1" bgcolor="#FFA3AB">
		<img src="images/bg.gif" width="1" height="1" alt="">
	</td>
    <td width="100%" height="360" align="center" valign="middle">
	  <table width="30%">
	  <tr>
		<td width="100%" valign="top" class="error">
		  <?php
		  if($msg!='')
				echo $msg;
		  ?>
		</td>
	   </tr>
	  </table>
	  <form action="" method="post" name="frm" id="frm">
	  <table width="30%" border="1" cellpadding="0" cellspacing="0" style="border-collapse :collapse" align="center">
		<tr>
		<td>
		 <table width="100%" border="0" cellpadding="8" cellspacing="0" style="border-collapse :collapse" align="center">
			<tr class="bgc">
			 <td width="37%" align="right" class="label_name1">From Date :</td>
			 <td width="63%" align="center">
			  <input type="text" name="fdate" id="fdate" value="<?php if(isset($_POST['fdate'])) echo $_POST['fdate']?>" class="inptbox" size="12" />
			  <a href="javascript:popUpCalendar(document.getElementById('fdate'),document.getElementById('fdate'),'yyyy-mm-dd');">
			   <img src="images/calendar.png" align="absmiddle" alt="From Date" border="0">
			  </a>
			 </td>
			</tr>
			<tr class="bgc2">
			 <td width="37%" align="right" class="label_name1">To Date :</td>
			 <td align="center">
			  <input type="text" name="tdate" id="tdate" value="<?php if(isset($_POST['tdate'])) echo $_POST['tdate']?>" class="inptbox" size="12" />
			  <a href="javascript:popUpCalendar(document.getElementById('tdate'),document.getElementById('tdate'),'yyyy-mm-dd');">
			   <img src="images/calendar.png" align="absmiddle" alt="To Date" border="0">
			  </a>
			 </td>
			</tr>
			<tr class="bgc">
			 <td colspan="2" align="center">
			 <input name="submit" type="submit" class="button1" value="Submit Request" />
			 </td> 
			</tr>
		</table>
	   </td>
	  </tr>
	 </table>
	</form>
<?php include "include/admin_footer.php";?>
