<?php
include "include/admin_header.php";
include "redirect_to_adminlogin.php";
include "../include/classes/functions_login.php";
global $dbc;
$msgFirstname = $msgLastname = $msgAddress = $msgSuburb = $msgPostcode = $msgPhone = $msgEmail = $msgPassword = $msgConfirmPassword = "";

if(isset($_POST['submit']) && $_POST['submit']=="Submit")
{
    if(!isset($_GET['id']) || $_GET['id']=="")
    {
        $result = validateMember($_POST['member_firstname'], $_POST['member_lastname'], $_POST['member_address'], $_POST['member_suburb'], $_POST['member_postcode'], $_POST['member_telephone'], $_POST['member_email']);
        $passwordResult = validateNewPassword($_POST['password'],$_POST['confirm_password']);
    }
    else
    {
        $passwordResult['errors'] = false;
        $result = validateMember($_POST['member_firstname'], $_POST['member_lastname'], $_POST['member_address'], $_POST['member_suburb'], $_POST['member_postcode'], $_POST['member_telephone'], $_POST['member_email'],$_GET['id']);
    }
	if($result['errors'] != 1 && !$passwordResult['errors'])
	{
        $name = $_POST['member_firstname'] ." ".$_POST['member_lastname'];
		if(!isset($_GET['id']) || $_GET['id']=="")
		{
            if(registerMember($name,$_POST['member_firstname'],$_POST['member_lastname'],$_POST['member_address'],$_POST['member_suburb'],$_POST['member_postcode'],$_POST['member_telephone'],$_POST['member_email'],$_POST['password']))
                header("Location: member_listing.php?opt=1");
        }
		else
		{
		    if(updateMember($_GET['id'],$name,$_POST['member_firstname'],$_POST['member_lastname'],$_POST['member_address'],$_POST['member_suburb'],$_POST['member_postcode'],$_POST['member_telephone'],$_POST['member_email']))
                header("Location: member_listing.php?opt=2");
        }
        exit;
    }
	else
    {
        include "../include/set_error_msg.php";
    }
}
if(count($_POST)<=0 && isset($_GET['id']) && $_GET['id']!="")
{
	$row=$dbc->getSingleRow("Select * from cpefs_members where member_id=?",__LINE__,__FILE__,array("i",&$_GET['id']));
	$_POST['member_email']=show_text($row['member_email']);
	$_POST['member_firstname']=show_text($row['member_firstname']);
	$_POST['member_lastname']=show_text($row['member_lastname']);
	$_POST['member_address']=show_text($row['member_address']);
	$_POST['member_suburb']=show_text($row['member_suburb']);
	$_POST['member_postcode']=show_text($row['member_postcode']);
	$_POST['member_telephone']=show_text($row['member_telephone']);

	$_SESSION['backpage']="member_listing.php#$_GET[id]";
}
?>
<table width="98%" height="100%" cellpadding="0"  cellspacing="0">
  <tr>
    <td colspan="3"><table width="100%"  cellspacing="0" cellpadding="0">
        <tr>
          <td class="body_head1">&nbsp;</td>
          <td align="center" class="body_bg1">Manage Members</td>
          <td class="body_head2">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#FFA3AB"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    <td height="360" width="100%" align="center" valign="middle">
    <form name="frm" action="" method="post" >
        <table width="95%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
		  <tr>
			<td align="left" valign="top"><table width="100%" cellpadding="0"  cellspacing="0" bordercolor="#CCD5E6">
              <tr class="bgc1"> 
                <td height="30" align="right" valign="middle" class="label_name1"><span style='color:red'>*</span>Member First Name :</td>
                <td align="left" valign="middle"><input name="member_firstname" size="40" maxlength="100" type="text" class="inptbox" value="<?php if(isset($_POST["member_firstname"])) echo $_POST["member_firstname"]?>"><?php echo $msgFirstname ?></td>
              </tr>
			  <tr class="bgc"> 
                <td height="30" align="right" valign="middle" class="label_name1"><span style='color:red'>*</span>Member Last Name :</td>
                <td align="left" valign="middle"> <input name="member_lastname" size="40" maxlength="100" type="text" class="inptbox" value="<?php if(isset($_POST["member_lastname"])) echo $_POST["member_lastname"]?>"><?php echo $msgLastname ?></td>
              </tr>
              <tr class="bgc1"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style='color:red'>*</span>Address
                  : </td>
                <td align="left" valign="middle"><textarea name="member_address" rows="4" cols="81"><?php if(isset($_POST["member_address"])) echo $_POST['member_address']?></textarea><?php echo $msgAddress ?></td>
              </tr>
              <tr class="bgc"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style='color:red'>*</span>Suburb
                  : </td>
                <td align="left" valign="middle"><input name="member_suburb"  maxlength="20" type="text" class="inptbox"  value="<?php if(isset($_POST["member_suburb"])) echo $_POST["member_suburb"]?>"><?php echo $msgSuburb ?></td>
              </tr>
              <tr class="bgc1"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style='color:red'>*</span>Postcode
                  : </td>
                <td align="left" valign="middle"><input name="member_postcode" maxlength="20" type="text" class="inptbox"  value="<?php if(isset($_POST["member_postcode"])) echo $_POST["member_postcode"]?>"><?php echo $msgPostcode ?></td>
              </tr>
              <tr class="bgc"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style='color:red'>*</span>Telephone
                  : </td>
                <td align="left" valign="middle"><input name="member_telephone"  maxlength="20" type="text" class="inptbox"  value="<?php if(isset($_POST["member_telephone"])) echo $_POST["member_telephone"]?>"><?php echo $msgPhone ?></td>
              </tr>
              <tr class="bgc1"> 
                <td height="30" align="right" valign="middle" class="label_name1"><span style='color:red'>*</span>Email Address
                   :</td>
                <td align="left" valign="middle"> <input name="member_email"  size="40" maxlength="150" type="text" class="inptbox" value="<?php if(isset($_POST["member_email"])) echo $_POST["member_email"]?>"><?php echo $msgEmail ?></td>
              </tr>
			  <?php
				if(!isset($_GET['id']) || $_GET['id']=="")
				{
			  ?>
			  			  	
              <tr class="bgc"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style='color:red'>*</span>Password
                  : </td>
                <td align="left" valign="middle"><input name="password"  maxlength="50" type="password" class="inptbox"  value="<?php if(isset($_POST["password"])) echo $_POST["password"]?>"><?php echo $msgPassword ?></td>
              </tr>
              <tr class="bgc1"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style='color:red'>*</span>Confirm Password
                  : </td>
                <td align="left" valign="middle"><input name="confirm_password"  maxlength="50" type="password" class="inptbox"  value="<?php if(isset($_POST["confirm_password"])) echo $_POST["confirm_password"]?>"><?php echo $msgConfirmPassword ?></td>
              </tr>
			  	<?php
			  		}
				?>
              <tr class="bgc"> 
                <td height="30" colspan="2" align="center" valign="middle" > 
                 <?php
				if($_SESSION['IS_ADMIN']==1)
                   echo '<input name="submit" type="submit" class="button1" value="Submit">';
				?>
					<input name="back" type="button" class="button1" value="Back" onClick="window.location.href='<?php echo $_SESSION['backpage']?>'"></td>
              </tr>
            </table></td>
		</tr>
        </table>
    </form>
<?php
	include "include/admin_footer.php";
?>