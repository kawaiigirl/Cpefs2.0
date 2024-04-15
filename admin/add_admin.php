<?php
include "include/admin_header.php";

include "../inc/functions_login.php";
include("redirect_to_adminlogin.php");
global $dbc;
$msgName = $msgEmail = $msgPassword = $msgPassword2 = "";

if(isset($_POST['submit']) && $_POST['submit']=="Submit")
{
    if(!isset($_GET['id']) || $_GET['id']=="")
        $result = validateAdmin($_POST['name'],$_POST['email'],$_POST['password'],$_POST['confirm_password']);
    else
        $result = validateAdmin($_POST['name'],$_POST['email']);
    if($result['errors'] != 1 && $result['pwd_errors'] != 1)
    {

        if(!isset($_GET['id']) || $_GET['id']=="")
        {
            if(registerAdmin($_POST['name'],$_POST['is_admin'],$_POST['email'],$_POST['password']))
                header("Location: admin_listing.php?opt=1");
        }
        else
        {
            if(updateAdmin($_GET['id'],$_POST['name'],$_POST['is_admin'],$_POST['email']))
                header("Location: admin_listing.php?opt=2");
        }
        exit;
    }
    else
    {
        include "../inc/set_error_msg.php";
    }
}
if(count($_POST)<=0 && isset($_GET['id']) && $_GET['id']!="")
{
	$row=$dbc->getSingleRow("Select * from cpefs_admin where id=?",__LINE__,__FILE__,array("i",&$_GET['id']));
	$_POST['email']=$row['email'];
	$_POST['name']=$row['name'];
	$_POST['is_admin']=$row['is_admin'];
	$_SESSION['backpage']="admin_listing.php#$_GET[id]";
}
?>
<table width="98%" height="100%" cellpadding="0"  cellspacing="0">
  <tr>
    <td colspan="3"><table width="100%"  cellspacing="0" cellpadding="0">
        <tr>
          <td class="body_head1">&nbsp;</td>
          <td align="center" class="body_bg1">Add Admin User</td>
          <td class="body_head2">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#FFA3AB"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    <td height="360" width="100%" align="center" valign="middle">
<form name="frm" action="" method="post">
<table width="450" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
		  <tr>
			  <td class="heading" height="30">Manage Admin Detail</td>
		  </tr>
		  <tr>
			<td align="left" valign="top"><table width="100%" cellpadding="0"  cellspacing="0" bordercolor="#CCD5E6">
              <tr class="bgc"> 
                <td height="30" align="right" valign="middle" class="label_name1"><span style="color:red">*</span><label for='name'>User Name :</label></td>
                <td align="left" valign="middle"> <input name="name" id='name' size="40" maxlength="100" type="text" class="inptbox" value="<?php if(isset($_POST["name"])) echo $_POST["name"]?>"><?php echo $msgName ?></td>
              </tr>
              <tr class="bgc1"> 
                <td height="30" align="right" valign="middle" class="label_name1"><span style="color:red">*</span><label for='email'>Email Address :</label></td>
                <td align="left" valign="middle"> <input name="email" id='email' size="40" maxlength="150" type="text" class="inptbox" value="<?php if(isset($_POST["email"])) echo $_POST["email"] ?>"><?php echo $msgEmail ?></td>
              </tr>
              <tr class="bgc"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style="color:red">*</span><label for='password'>Password : </label></td>
                <td align="left" valign="middle"><input name="password" id='password' maxlength="50" type="password" class="inptbox"  value="<?php if(isset($_POST["password"])) echo $_POST["password"]?>"><?php echo $msgPassword ?></td>
              </tr>
              <tr class="bgc1"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style="color:red">*</span><label for='confirm_password'>Confirm Password : </label></td>
                <td align="left" valign="middle"><input name="confirm_password" id='confirm_password' maxlength="50" type="password" class="inptbox"  value="<?php if(isset($_POST["confirm_password"])) echo $_POST["confirm_password"]?>"><?php echo $msgPassword2 ?></td>
              </tr>
			  <tr class="bgc"> 
                  <td height="30" align="right" valign="middle" class="label_name1"><label for='is_admin'>Account Type :</label></td>
                <td align="left" valign="middle">
				 <input name="is_admin" id='is_admin' type="radio" value="1" <?php if(isset( $_POST["is_admin"])&& $_POST["is_admin"]==1) echo "Checked";elseif(!isset( $_POST["is_admin"])) echo "Checked";?> />Admin
				 <input name="is_admin" id='is_admin' type="radio" value="0" <?php if(isset( $_POST["is_admin"]) && $_POST["is_admin"]==0) echo "Checked";?> />Director
				</td>
              </tr>
              <tr class="bgc2"> 
                <td height="30" colspan="2" align="center" valign="middle" > 
				<?php
				if($_SESSION['IS_ADMIN']==1)
                   echo '<input name="submit" type="submit" class="button1" value="Submit">';
				?>
					<input name="back" type="button" class="button1" value="Back" onClick="window.location.href='<?php echo $_SESSION['backpage']?>'"></td>
              </tr>
            </table></td>
		</tr>
</table>	</form>
<?php
	include "include/admin_footer.php";
?>