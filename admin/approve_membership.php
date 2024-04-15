<?php
include "include/admin_header.php";
include("redirect_to_adminlogin.php");


$f=0;

global $dbc;
if($_GET["id"]=="")
{
	header("Location: member_listing.php");
	exit;
}

if(isset($_POST['submit']) && $_POST['submit']=="Submit")
{
	
	if(isset($_POST["approve"]) && $_POST["approve"]!="")
	{
		$dbc->update("Update cpefs_members set member_status=2 where member_id=?",__LINE__,__FILE__,array("i",&$_GET['id']));
		
		$row = $dbc->getSingleRow(" Select * from cpefs_members where member_id=?",__LINE__,__FILE__,array("i",&$_GET['id']));

		$member_name=getFirstName($row["member_name"]);
		
		$body="Congratulations $member_name,<br><br> Your membership has been approved. <br><br>Regards,<br>CPEFS";
		$altBody = "Congratulations $member_name, 
        Your membership has been approved.
        
        Regards,
        CPEFS";
		$subject = "CPEFS - Registration Approved";
        sendEmail(0,$row["member_email"],$subject,$body,$altBody,"approve_membership","approve membership","Member");

    }
	if($_POST["admin_user"]!="")
	{
		$sql = " Select * from cpefs_members where member_id =?";
		$row = $dbc->getSingleRow($sql,__LINE__,__FILE__,array("i", $_GET['id']));
		$dbc->insertPDO("Insert into cpefs_admin set name = ? , password = ? , email = ?",__LINE__,__FILE__,array( $row['member_name'],$row['member_password'],$row['member_email']));
	}	
	
	header("Location: member_listing.php#$_GET[id]");
	exit;

}

if($_GET['id']!="")
{
	$row=$dbc->getSingleRow("Select * from cpefs_members where member_id=?",__LINE__,__FILE__,array("i",& $_GET['id']));
	$_POST['member_name']=$row['member_name'];
	$_SESSION['backpage']="member_listing.php#$_GET[id]";
}

?>
<table width="98%" height="100%" cellpadding="0"  cellspacing="0">
  <tr>
    <td colspan="3"><table width="100%"  cellspacing="0" cellpadding="0">
        <tr>
          <td class="body_head1">&nbsp;</td>
          <td align="center" class="body_bg1">Approve Member: <b><?php echo $_POST["member_name"]?></b></td>
          <td class="body_head2">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#FFA3AB"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    <td height="360" width="100%" align="center" valign="middle">
        <form name="frm" action="" method="post">
<table width="350" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
		  <tr>
			  <td class="heading" height="30">Approve Membership</td>
		  </tr>
		  <tr>
			<td align="left" valign="top"><table width="100%" cellpadding="0"  cellspacing="0" bordercolor="#CCD5E6">
              <tr class="bgc"> 
                <td height="30" align="right" valign="middle" class="label_name1" width="40%">Approve Member 
                   :</td>
                <td align="left" valign="middle"> <input name="approve" type="checkbox" value="1" <?php if(isset($_POST["approve"])&&$_POST["approve"]==1){ echo "checked";}?> ></td>
              </tr>
              <tr class="bgc1"> 
                <td height="30" align="right" valign="middle" class="label_name1">Is Administrator
                   :</td>
                <td align="left" valign="middle"> <input name="admin_user" type="checkbox" value="1" <?php if(isset($_POST["admin_user"]) && $_POST["admin_user"]==1){ echo "checked";}?> ></td>
              </tr>
              <tr class="bgc"> 
                <td height="30" colspan="2" align="center" valign="middle" > 
                  <input name="submit" type="submit" class="button1" value="Submit">
				&nbsp;&nbsp; 
					<input name="back" type="button" class="button1" value="Back" onClick="window.location.href='<?php echo $_SESSION['backpage']?>'"></td>
              </tr>
            </table></td>
		</tr>
</table>
</form>
<?php
	include "include/admin_footer.php";
?>