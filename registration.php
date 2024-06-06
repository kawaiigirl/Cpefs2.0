<?php
include "include/base_includes.php";
include "include/view/layout.php";
include_once "include/classes/functions_login.php";
include_once "include/functions_booking.php";
include_once "include/phpmailer/PHPMailer.php";
use ReCaptcha\ReCaptcha;
require("include/reCaptcha/autoload.php");

$success = $msgRecaptcha = "";
$errors= array();
$focusOnError="";
if(isset($_POST['Submit']))
{
    $recaptcha = new ReCaptcha(SECRET_KEY);
    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
    if (!$resp->isSuccess()) //!$resp->is_valid
    {
        // What happens when the CAPTCHA was entered incorrectly
        $msgRecaptcha="<span class='required error'>[reCAPTCHA verification failed. Please try again.]</span>";
        $focusOnError="recaptcha";
    }
    else
    {
        $errors = validateMember($_POST['member_firstname'],$_POST['member_lastname'],$_POST['member_address'],$_POST['member_suburb'],$_POST['member_postcode'],$_POST['member_telephone'],$_POST['member_email']);
        $passwordResult = validateNewPassword($_POST['member_password'], $_POST['member_password1']);

        if(!$errors['errors'] && !$passwordResult['errors'])
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
            $errors = array_merge($errors, $passwordResult);
            $focusOnError = $errors['focus'];
        }
    }
}

require "include/view/registration_view.php";
