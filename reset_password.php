<?php
include "include/base_includes.php";
include "include/view/layout.php";
include_once "include/classes/functions_login.php";
include_once "include/functions_booking.php";
const TYPE = "member";
if (isset($_GET["token"]) && isset($_GET["email"]) )
{
    require "include/view/reset_password_view.php";
}
else
{
    header("location:login.php");
}