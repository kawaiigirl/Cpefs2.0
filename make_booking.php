<?php
include "include/base_includes.php";
include "include/view/layout.php";
global $dbc;

$success= "";
$focusOnError="unit_id";
$sql=$dbc->getSingleRow("Select active from cpefs_members Where member_id =?",__LINE__,__FILE__,array("i",&$_SESSION['member_id']));

if(!$sql || $sql['active'] != 1)
{
    $success="<span style='color:red;'>&nbsp;Current Member is unable to book a unit, please <a href='contact.php'>contact</a> the administrator.</span> ";
}
elseif(count($_POST)>0)
{
    $validationResult = Booking::validateInput($_SESSION['member_id'], $_POST['unit_id'], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['phone'], $_POST['check_in_date'], $_POST['nights']);
    $errors = $validationResult['errMsg'];
    $focusOnError = $validationResult['focusOnError'];

    if($focusOnError =='')
    {
        $newBooking = new Booking($_SESSION['member_id'], $_POST['unit_id'], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['phone'], $_POST['check_in_date'], $_POST['nights']);
        $validationResult = $newBooking->validateBooking();
        $errors = $validationResult['errMsg'];
        $focusOnError = $validationResult['focusOnError'];
        if((isset($_POST['agree']) && $_POST['agree'] != "yes") || !isset($_POST['agree']))
        {
            $errors['agree'] = "&nbsp;[Required]";
            if($focusOnError == '')
            {
                $focusOnError = 'agree';
            }
        }
        if($focusOnError == '')
        {
            $newBooking->addUserBooking($newBooking->getName());
            $success = "&nbsp;Thank You! Your booking request has been sent to the administrator.";
        }
    }
}
else
{
    $data=$dbc->getSingleRow("Select * From cpefs_members Where member_id=?",__LINE__,__FILE__,array("i",&$_SESSION['member_id']));
    $_POST['firstname']=$data['member_firstname'];
    $_POST['lastname']=$data['member_lastname'];
    $_POST['email']=$data['member_email'];
    $_POST['phone']=$data['member_telephone'];
}
require "include/view/make_booking_view.php";