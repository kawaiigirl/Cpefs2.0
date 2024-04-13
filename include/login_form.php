<?php

use ReCaptcha\ReCaptcha;
include_once "include/classes/functions_login.php";

require("include/reCaptcha/autoload.php");
$emsgEmail = $emsgPassword = $logerr = $errMsg = $msg =$msgRecaptcha= "";

//login page


if(isset($_POST['loginbtn']))
{
    $recaptchaUsed = false;

    if(isset($_SESSION['login_attempts']))
    {
        if(isset($_POST['g-recaptcha-response']))
        {
            $recaptcha = new ReCaptcha(SECRET_KEY);
            $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            if(!$resp->isSuccess())
            {
                // What happens when the CAPTCHA was entered incorrectly
                $msgRecaptcha = "<span class='required'>[reCAPTCHA verification failed. <br>Please try again.]</span>";
            }
            else
            {
                $recaptchaUsed = true;
            }
        }
        else
        {
            $msgRecaptcha = "<span class='required'>[reCAPTCHA verification failed. <br>Please try again.]</span>";
        }
    }

    if ($recaptchaUsed || !isset($_SESSION['login_attempts']))
    {
        $errorArray = validateMemberLogin($_POST['mem_email'], $_POST['mem_pass']);
        if($errorArray['error'] == false)
        {
            $verifyResult = verifyMemberLogin($_POST['mem_email'], $_POST['mem_pass']);
            if($verifyResult['success'])
            {
                $member = GetMember($_POST['mem_email']);

                if($member['member_status'] == 0)
                    $errMsg = "<span style='color:red'>&nbsp;[Account Un-verified]</span>";
                elseif($member['member_status'] == 1)
                    $errMsg = "<span style='color:red'>&nbsp;[Account Cancelled]</span>";
                elseif($verifyResult['password_expired'] == true)
                {
                    header("Location: password_expired.php");
                    exit;
                }
                else
                {
                    $_SESSION['member_id']=$member['member_id'];
                    $_SESSION['mem_name']=$member['member_name'];
                    header("Location: myaccount.php");
                    exit;
                }
            }
            else
            {
                $errMsg = "<span style='color:red'>&nbsp;[Invalid Login]</span>";
                if(isset($_SESSION['login_attempts']))
                    $_SESSION['login_attempts']++;
                else
                    $_SESSION['login_attempts']=1;
            }
        }
        else
        {
            if(isset($errorArray['email']))
                $emsgEmail = $errorArray['email'];
            if(isset($errorArray['password']))
                $emsgPassword = $errorArray['password'];
        }
    }
}
?>
<form name="logform" method="post" action="" onSubmit="">
    <p>
        <label for="mem_email">Email</label><input type="text" name="mem_email" id="mem_email" value="<?php if(isset($_POST['mem_email']))echo $_POST['mem_email'];?>">&nbsp;<?php echo $emsgEmail?>
    </p>

    <label></label>
    <p>Password<br>
        <label>
            <input type="password" name="mem_pass" id="mem_pass" value="<?php if(isset($_POST['mem_pass'])) echo $_POST['mem_pass'];?>"><?php echo $emsgPassword?>
        </label>
    </p>
    <?php
    if(isset($_SESSION['login_attempts']))
    {
        ?><script type="text/javascript"
        src="https://www.google.com/recaptcha/api.js?hl=en_GB">
    </script>
        <div class="g-recaptcha" data-size='compact'  data-sitekey="<?php echo SITE_KEY; ?>"></div>

        <?php echo $msgRecaptcha?>
        <?php
    }
    ?>
    <p>
        <label>
            <input type="submit" name="loginbtn" id="loginbtn" value="Submit">&nbsp;&nbsp;<?php echo $errMsg?>
        </label>
    </p>
</form>
<a href="forgot-password.php" class="Grey">Forgot password</a> | <a href="registration.php" class="Grey">Join</a> </div>
