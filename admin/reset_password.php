<?php
include "include/admin_header.php";
include("redirect_to_adminlogin.php");
require_once "../inc/validator.php";
//todo send change password email
$f=0;
$validator=new Validator;
global $database;
if(isset($_GET["id"]) && $_GET["id"]=="")
{
	header("Location: member_listing.php");
	exit;
}

if(isset($_POST['submit']) && $_POST['submit']=="Submit")
{
	
	if(!$validator->General($_POST['old_password'],'Please specify Old Password'))
	{
		if($f==0)
		{
			$f=1;
		}
	}
	if(!$validator->General($_POST['new_password'],'Please specify New Password'))
	{
		if($f==0)
		{
			$f=2;
		}
	}
	
	if(!$validator->General($_POST['confirm_password'],'Please Confirm New Password'))
	{
		if($f==0)
		{
			$f=3;
		}
	}
	elseif($_POST['confirm_password']!=$_POST['new_password'])
	{
		if($f==0)
		{
			$f=3;
		}
		$validator->addError("Please Confirm New Password");
	}

	if(!$validator->foundErrors())
	{

		$nn=getNumRows("Select * From $database[members] where member_password='" .  base64_encode( $_POST["old_password"]) ."' and member_id = $_GET[id]");

		if($nn<=0)
		{
			$validator->addError("Password Mismatch");
			if($f==0)
			{
				$f=1;
			}
		}
	}
	
	if(!$validator->foundErrors())
	{

		$pass=base64_encode($_POST['new_password']);
		$qry = "  member_password='$pass'";
		
		update("Update $database[members] set $qry where member_id=$_GET[id]");
		header("Location: member_listing.php#$_GET[id]");
		exit;

	}
}
if($_GET['id']!="")
{
	$row=getSingleData("Select * from $database[members] where member_id=$_GET[id]");
	$_POST['member_name']=$row['member_name'];
	$_SESSION['backpage']="member_listing.php#$_GET[id]";
}

?>
<table width="98%" height="100%" cellpadding="0"  cellspacing="0">
  <tr>
    <td colspan="3"><table width="100%"  cellspacing="0" cellpadding="0">
        <tr>
          <td class="body_head1">&nbsp;</td>
          <td align="center" class="body_bg1">Reset Password: <b><?php echo $_POST["member_name"]?></b></td>
          <td class="body_head2">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#FFA3AB"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    <td height="360" width="100%" align="center" valign="middle">
		<?php
			if($validator->foundErrors())
			{
		?>
		<table width="450" border="0" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
		  <tr>
		  	<td>
				<?php $validator->listErrors(); ?>
			</td>
		</tr>
		</table>
		<?php
			}
		?>
<table width="450" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
  <form name="frm" action="" method="post">
		  <tr>
			  <td class="heading" height="30">Reset password</td>
		  </tr>
		  <tr>
			<td align="left" valign="top"><table width="100%" cellpadding="0"  cellspacing="0" bordercolor="#CCD5E6">
              <tr class="bgc"> 
                <td height="30" align="right" valign="middle" class="label_name1"><font color="#FF0000">*</font>Old 
                  Password :</td>
                <td align="left" valign="middle"> <input name="old_password" size="30" maxlength="50" type="password" class="inptbox" value="<?php if(isset($_POST["old_password"])) echo $_POST["old_password"]?>"></td>
              </tr>
              <tr class="bgc1">
                <td height="30" align="right" valign="middle" class="label_name1"><font color="#FF0000">*</font>New Password
                   :</td>
                <td align="left" valign="middle"> <input name="new_password"  size="30" maxlength="50" type="password" class="inptbox" value="<?php if(isset($_POST["new_password"])) echo $_POST["new_password"]?>"></td>
              </tr>
              <tr class="bgc"> 
                <td align="right" height="30" valign="middle" class="label_name1"><font color="#FF0000">*</font>Confirm New Password : </td>
                <td align="left" valign="middle"><input name="confirm_password" size="30" maxlength="50" type="password" class="inptbox"  value="<?php if(isset($_POST["confirm_password"])) echo $_POST["confirm_password"]?>"></td>
              </tr>
              <tr class="bgc1"> 
                <td height="30" colspan="2" align="center" valign="middle" > 
                  <input name="submit" type="submit" class="button1" value="Submit">
				&nbsp;&nbsp; 
					<input name="back" type="button" class="button1" value="Back" onClick="window.location.href='<?php echo $_SESSION['backpage']?>'"></td>
              </tr>
            </table></td>
		</tr>
	</form>
</table>
<?php
	switch ($f) 
	{
		case 1:
			set_focus("frm.old_password");
			break;
		case 2:
			set_focus("frm.new_password");
			break;
		case 3:
			set_focus("frm.confirm_password");
			break;
		default:
			set_focus("frm.old_password");
			break;
	}
	include "include/admin_footer.php";
?>