<?php
include "include/base_includes.php";
include "include/view/layout.php";
include_once "include/classes/functions_login.php";
use ReCaptcha\ReCaptcha;
require("include/reCaptcha/autoload.php");

$success = $msgRecaptcha = "";
$errors= array();
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
            include "include/set_error_msg.php";
        }
    }
}

require "include/view/registration_view.php";
