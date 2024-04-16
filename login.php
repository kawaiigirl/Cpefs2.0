<?php
include "include/base_includes.php";
include "include/view/layout.php";
use ReCaptcha\ReCaptcha;
include_once "include/classes/functions_login.php";

require("include/reCaptcha/autoload.php");
$eMsgEmail = $eMsgPassword = $logErr = $errMsg = $msg =$msgRecaptcha= "";

if(isset($_POST['submit']))
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
        $errorArray = validateMemberLogin($_POST['email'], $_POST['email']);
        if(!$errorArray['error'])
        {
            $verifyResult = verifyMemberLogin($_POST['email'], $_POST['password']);
            if($verifyResult['success'])
            {
                $member = GetMember($_POST['email']);

                if($member['member_status'] == 0)
                    $errMsg = "<span style='color:red'>&nbsp;[Account Un-verified]</span>";
                elseif($member['member_status'] == 1)
                    $errMsg = "<span style='color:red'>&nbsp;[Account Cancelled]</span>";
                elseif($verifyResult['password_expired'])
                {
                    header("Location: password_expired.php");
                    exit;
                }
                else
                {

                    $_SESSION['member_id']=$member['member_id'];
                    $_SESSION['mem_name']=$member['member_name'];
                    header("Location: my_account.php");
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
                $eMsgEmail = $errorArray['email'];
            if(isset($errorArray['password']))
                $eMsgPassword = $errorArray['password'];
        }
    }
}

require "include/view/login_view.php";