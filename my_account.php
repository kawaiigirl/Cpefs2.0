<?php
include "include/base_includes.php";
include "include/authenticator.php";
include "include/view/layout.php";

global $dbc;

$success = $msgFirstname = $msgLastname = $msgAddress = $msgSuburb = $msgPostcode = $msgPhone = $msgEmail= "";

if(isset($_POST['Submit']))
{
    $result = validateMember($_POST['member_firstname'], $_POST['member_lastname'], $_POST['member_address'], $_POST['member_suburb'], $_POST['member_postcode'], $_POST['member_telephone'], $_POST['member_email'],$_SESSION['member_id']);

    if($result['errors'] != 1) {
        $name = $_POST['member_firstname'] . " " . $_POST['member_lastname'];
        updateMember($_SESSION['member_id'], $name, $_POST['member_firstname'], $_POST['member_lastname'], $_POST['member_address'], $_POST['member_suburb'], $_POST['member_postcode'], $_POST['member_telephone'], $_POST['member_email']);
        $success = "<span style='color:red'>&nbsp;Your account has been successfully updated</span>";
    }
    else {
        include "inc/set_error_msg.php";
    }
}
else
{
    $data=$dbc->getSingleRow("Select * From cpefs_members Where member_id=?",__LINE__,__FILE__,array("i",& $_SESSION['member_id']));
    $_POST['member_firstname']=$data['member_firstname'];
    $_POST['member_lastname']=$data['member_lastname'];
    $_POST['member_address']=$data['member_address'];
    $_POST['member_suburb']=$data['member_suburb'];
    $_POST['member_postcode']=$data['member_postcode'];
    $_POST['member_telephone']=$data['member_telephone'];
    $_POST['member_email']=$data['member_email'];

}
require "include/view/my_account_view.php";