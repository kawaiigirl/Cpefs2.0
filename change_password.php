<?php
include "include/base_includes.php";
include "include/authenticator.php";
include "include/view/layout.php";
include_once "include/classes/functions_login.php";
include_once "include/functions_booking.php";

$success = "";

$msgOldPassword = $msgNewPassword = $msgConfirmPassword = "";

if(isset($_POST['Submit']))
{
    $result = validateUpdatedPassword($_POST['newPassword'], $_POST['confirmPassword'], $_POST['oldPassword'], $_SESSION['member_id']);

    if($result['errors'] != 1) {
        updateMemberPasswordFromID($_POST['newPassword'], $_SESSION['member_id']);
        $success = "<span style='color:red'>&nbsp;Your password has been successfully updated</span>";
    }
    else {
        include "include/set_error_msg.php";
    }
}

include "include/view/change_password_view.php";

