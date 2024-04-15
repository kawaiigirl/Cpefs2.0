<?php
session_start();
const incPATH = "../inc/";
include "../inc/common.php";
include "../inc/functions_login.php";

$errors = false;

if(isset($_POST['submit']) && $_POST['submit']=="Login")
{

    if($_POST['email']=="" || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        $errors[]="Please Specify a valid Email Address";
    }
    if($_POST['password']=="")
    {
        $errors[]="Please Specify Password";
    }

    if(!$errors)
    {
        $result = verifyAdminLogin($_POST['email'],$_POST['password']);
       if($result['success'] != false)
       {
           if($result['password_expired'] ==true)
           {
               header("Location: password_expired.php");
           }
           else {
               if ($_SESSION['IS_ADMIN'] != 1 && $_SESSION['IS_ADMIN'] != 0)
                   echo "<script>window.location.href='unit_bookings.php'</script>";
               else
                   echo "<script>window.location.href='home.php'</script>";
           }
       }
       else
       {
           $errors[] = "Incorrect Login! Wrong Username or Password";
       }
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Administration:: <?php echo SITE_TITLE?> </title>
 <link href="style.css" rel="stylesheet" type="text/css">
<body > 
<table width="100%" height="90	%" cellpadding="0"  cellspacing="0">
  <tr>
    <td colspan="2" align="left" valign="top">
	  <table width="100%"  cellspacing="0" cellpadding="0">
        <tr class="bg1">
          <td class="admin_text">&nbsp;</td>
          <td align="right" valign="middle" class="bg1"><a href=<?php echo SITE_URL . "/index.php"; ?> class="link1">:: 
            My Site&nbsp;</a></td>
        </tr>
        <tr> 
          <td colspan="2" class="bg3">&nbsp;</td>
        </tr>
      </table>
	</td>
  </tr>
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#FFFFFF"></td>
    <td width="100%" align="center" valign="top" bgcolor="#FFFFFF">
	<!--  Body Starts here -->
		<table width="98%" height="100%" cellpadding="0"  cellspacing="0">
			  <tr>
				<td colspan="3"><table width="100%"  cellspacing="0" cellpadding="0">
					<tr>
					  <td class="body_head1">&nbsp;</td>
					  <td class="body_bg1">&nbsp;</td>
					  <td class="body_head2" align="right">&nbsp;</td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td width="1" bgcolor="#FFA3AB"><img src="images/bg.gif" width="1" height="1" alt=""></td>
				
          <td width="100%" height="100%" align="center" valign="middle">
		    <table width="350"  cellspacing="0" cellpadding="0" border="0">
			  <tr>
		      <td class="error">
			  <?php
				  if(!$errors=="")
				  {
					  foreach($errors as $error)
					  {
						echo "<li>$error</li>";
					  }
    	    		}
			  
			  ?>
              </td>
			</tr>
			</table>
			<br><form name="frm" action="" method="POST">
            <table width="350" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000" style="border-collapse :collapse">

				  <tr>
					
                  <td class="heading">Admin Login</td>
				  </tr>
				  <tr>
					<td align="left" valign="top"><table width="100%"  cellspacing="0" cellpadding="0">
						<tr>
						  <td align="right" valign="middle">&nbsp;</td>
						  <td align="left" valign="middle">&nbsp;</td>
						</tr>
						<tr>
						  <td align="right" valign="middle" class="label_name1">Email Address :</td>
						  <td align="left" valign="middle"><input name="email" type="text" class="logform" <?php if(isset($_POST['email']) && $_POST['email']!==""){echo "value=$_POST[email]";} ?>></td>
						</tr>
						<tr>
						  <td align="right" valign="middle" class="label_name1">Password : </td>
						  <td align="left" valign="middle"><input name="password" type="password" class="logform" <?php if(isset($_POST['password']) && $_POST['password']!==""){echo "value=$_POST[password]";} ?>></td>
						</tr>
						<tr>
						  <td align="right" valign="middle">&nbsp;</td>
						  <td height="25" align="left" valign="middle"><input name="submit" type="submit" class="button1" value="Login"></td>
						</tr>
                        <tr>
                            <td></td>
                            <td><a href="forgot-password.php" style="font-size: small">Forgot password?</a></td>

                        </tr>
						<tr>
                            <td align="right" valign="middle"> </td>
						  <td align="left" valign="middle">&nbsp;</td>
						</tr>
					</table></td>
				  </tr>
				</table></form></td>
				<td width="1" bgcolor="#FFA3AB"><img src="images/bg.gif" width="1" height="1" alt=""></td>
			  </tr>
			  <tr>
				<td colspan="3"><table width="100%"  cellspacing="0" cellpadding="0">
					<tr>
					  <td class="body_foot1"><img src="images/bg.gif" width="1" height="1" alt=""></td>
					  <td class="body_bg2"><img src="images/bg.gif" width="1" height="1" alt=""></td>
					  <td class="body_foot2"><img src="images/bg.gif" width="1" height="1" alt=""></td>
					</tr>
				</table></td>
			  </tr>
			</table>
					</td>
			<td width="1" bgcolor="#FFA3AB"><img src="images/bg.gif" width="1" height="1" alt=""></td>
		  </tr>
		</table>
		<!-- Body Ends Here -->
	</td>
  </tr>
  <tr>
    <td colspan="2" align="left" valign="top"><?php include "include/footer.php"; ?></td>
  </tr>
</table>
<script type="javascript">document.frm.email.focus();</script>
</body>
</html>
