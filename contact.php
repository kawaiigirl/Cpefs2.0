<?php
use ReCaptcha\ReCaptcha;
include "include/base_includes.php";
include("include/view/layout.php");
include "include/phpmailer/PHPMailer.php";
$responseMsg = "";
if(isset($_POST['Submit'])) {

    require "include/reCaptcha/autoload.php";
    require "include/functions_booking.php";

    $mailto = get_admin_receive_email_id();
//todo fix this mess up
    $recaptcha = new ReCaptcha(SECRET_KEY);
    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

###############################################################
# Validation to check form data is sent
###############################################################

    $empty = 0;

    $name= $phone = $email = $message = "";
    if ($_POST['Name'] == "") {
        $empty++;
        $name = "n/a";
    }
    else
    {
        $name = $_POST['Name'];
    }
    if ($_POST['Telephone'] == "") {
        $empty++;
        $phone = "n/a";
    }
    else {
        $phone = $_POST['Telephone'];
    }
    if ($_POST['Email'] == "") {
        $empty++;
        $email = "n/a";
    }
    else
    {
        $email = $_POST['Email'];
    }
    if ($_POST['Message'] == "") {
        $empty=4;
        $message = "n/a";
    }
    else
    {
        $message = $_POST['Message'];
    }
    if (!$resp->isSuccess() || $empty>3)
    {
        $responseMsg = "There was an error with your input. Please try again.";
    }
    else
    {
        $date_now = date("F j, Y") . " at " . date("g:i a") . " (EST)";  //April 19th, 2004 at 11:05PM (EST)
        $msg = "Below is the result of your feedback form.  It was submitted by <a href = 'mailto:".$email."'>".$email."</a> on ". $date_now."\n<br><br>";

        $msg .= "Name: <b>".$name."</b>\n<br>";
        $msg .= "Telephone: <b>".$phone."</b>\n<br>";
        $msg .= "Email: <a href = 'mailto:".$email."'>".$email."</a>\n<br>";
        $msg .= "Message: <b>".$message."</b>\n<br>";

        sendEmail(0,$mailto,"CPEFS Online Enquiry",$msg,$msg,"contact.php","contactus","admin");

        $responseMsg = "Your enquiry has been sent.";
    }
}
require "include/view/contact_view.php";