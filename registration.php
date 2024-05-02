<?php
use ReCaptcha\ReCaptcha;
include "inc/common.php";
$title="CPEFS Holiday - Member Registration";
include "inc/header.php"; 
require("inc/reCaptcha/autoload.php");

$msgFirstname = $msgLastname = $msgAddress = $msgSuburb = $msgPostcode = $msgPhone = $msgEmail = $msgPassword = $msgPassword2 = $success = $msgRecaptcha = "";
if(isset($_POST['Submit']))
{
    $recaptcha = new ReCaptcha(SECRET_KEY);
    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
    if (!$resp->isSuccess()) //!$resp->is_valid
    {
        // What happens when the CAPTCHA was entered incorrectly
        $msgRecaptcha="<span class='required'>[reCAPTCHA verification failed. Please try again.]</span>";
    }
    else
    {
        $result = validateMember($_POST['member_firstname'],$_POST['member_lastname'],$_POST['member_address'],$_POST['member_suburb'],$_POST['member_postcode'],$_POST['member_telephone'],$_POST['member_email']);
        $passwordResult = validateNewPassword($_POST['member_password'], $_POST['member_password1']);
        if($result['errors'] != 1 && $result['pwd_errors'] != 1)
        {
            $name = $_POST['member_firstname'] ." ".$_POST['member_lastname'];
            if(registerMember($name,$_POST['member_firstname'],$_POST['member_lastname'],$_POST['member_address'],$_POST['member_suburb'],$_POST['member_postcode'],$_POST['member_telephone'],$_POST['member_email'],$_POST['member_password']))
            {
                $success= "<span style='color:red'>&nbsp;Thank You! Your account is under verification.</span>";
                // Send Admin an email
                sendAdminRegistrationEmail($name);
            }
        }
        else
        {
            include "inc/set_error_msg.php";
        }
    }
}
?>
<tr>
    <td colspan="2" valign="top"><div style="padding:15px">
      <h1>Member Registration</h1>
      <form name="form1" method="post" action="">
        <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
		<?php
		if($success=="")
		{
		?>
           <tr style="background-COLOR: #E2ECFE;">
            <td style="padding:3px;" width="30%" height="20" valign="middle">First Name</td>
            <td style="padding:3px;" width="70%" height="20" valign="middle"><label>
              <input type="text" name="member_firstname" id="member_firstname" value="<?php if(isset($_POST['member_firstname'])) echo $_POST['member_firstname']?>">
			  <?php echo $msgFirstname?>
            </label></td>
          </tr>
		  <tr>
            <td style="padding:3px;" width="30%" height="20" valign="middle">Last Name</td>
            <td style="padding:3px;" width="70%" height="20" valign="middle"><label>
              <input type="text" name="member_lastname" id="member_lastname" value="<?php if(isset($_POST['member_lastname'])) echo $_POST['member_lastname']?>">
			  <?php echo $msgLastname?>
            </label></td>
          </tr>
          <tr style="background-COLOR: #E2ECFE;">
            <td style="padding:3px;" width="30%" height="20" valign="middle">Address</td>
            <td style="padding:3px;" width="70%" height="20" valign="middle"><label>
              <input type="text" name="member_address" id="member_address" value="<?php if(isset($_POST['member_address'])) echo $_POST['member_address']?>">
			  <?php echo $msgAddress?>
              </label></td>
          </tr>
          <tr>
            <td style="padding:3px;" width="30%" height="20" valign="middle">Suburb</td>
            <td style="padding:3px;" width="70%" height="20" valign="middle"><label>
              <input type="text" name="member_suburb" id="member_suburb" value="<?php if(isset($_POST['member_suburb'])) echo $_POST['member_suburb']?>">
			  <?php echo $msgSuburb?>
              </label></td>
          </tr>
          <tr style="background-COLOR: #E2ECFE;">
            <td style="padding:3px;" width="30%" height="20" valign="middle">Postcode</td>
            <td style="padding:3px;" width="70%" height="20" valign="middle"><label>
              <input type="text" name="member_postcode" id="member_postcode" value="<?php if(isset($_POST['member_postcode'])) echo $_POST['member_postcode']?>">
			  <?php echo $msgPostcode?>
              </label></td>
          </tr>
          <tr>
            <td style="padding:3px;" width="30%" height="20" valign="middle">Telephone</td>
            <td style="padding:3px;" width="70%" height="20" valign="middle"><label>
              <input type="text" name="member_telephone" id="member_telephone" value="<?php if(isset($_POST['member_telephone'])) echo $_POST['member_telephone']?>">
              <?php echo $msgPhone?>
              </label></td>
          </tr>
          <tr style="background-COLOR: #E2ECFE;">
            <td style="padding:3px;" width="30%" height="20" valign="middle">Email</td>
            <td style="padding:3px;" width="70%" height="20" valign="middle"><label>
              <input type="text" name="member_email" id="member_email" value="<?php if(isset($_POST['member_email'])) echo $_POST['member_email']?>">
              <?php echo $msgEmail?>
              </label></td>
          </tr>
          <tr>
            <td style="padding:3px;" width="30%" height="20" valign="middle">Password</td>
            <td style="padding:3px;" width="70%" height="20" valign="middle"><label>
              <input type="password" name="member_password" id="member_password" value="<?php if(isset($_POST['member_password'])) echo $_POST['member_password']?>">
              <?php echo $msgPassword?>
              </label></td>
          </tr>
          <tr style="background-COLOR: #E2ECFE;">
            <td style="padding:3px;" width="30%" height="20" valign="middle">Verify password</td>
            <td style="padding:3px;" width="70%" height="20" valign="middle"><label>
              <input type="password" name="member_password1" id="member_password1" value="<?php if(isset($_POST['member_password1'])) echo $_POST['member_password1']?>">
              <?php echo $msgPassword2?>
              </label></td>
          </tr><tr><td width="100%" align="center" colspan="2" >
		 <div class="g-recaptcha" data-sitekey="<?php echo SITE_KEY; ?>"></div>
            <script type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js?hl=en_GB">
            </script>
		<?php echo $msgRecaptcha?></td>
		  </tr>
          <tr>
           
            <td colspan="2" align="center" height="20" valign="top"><label>
              <input type="submit" name="Submit" id="Submit" value="Submit">
            </label></td>
          </tr>
		  
		  <?php
		  }
		  else
		  	 echo '<tr><td colspan="2" valign="bottom" align="center">'.$success.'</td></tr>';
		  ?>
        </table>
      </form>
      <p>&nbsp;</p>
    </div></td>
  </tr>
<?php include "inc/footer.php"; ?>