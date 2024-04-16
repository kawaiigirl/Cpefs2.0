<?php
include "include/base_includes.php";
include "include/authenticator.php";
include "include/view/layout.php";

if(isset($_GET['id']))
{
    $booking=$dbc->getSingleRow("Select * From cpefs_units Where unit_status=1 And unit_id=?",__LINE__,__FILE__,array("i",& $_GET['id']));
}
else
{
    $booking=$dbc->getSingleRow("Select * From cpefs_units Where unit_status=1 And unit_id=1",__LINE__,__FILE__);
    $_GET['id'] = 1;
}
require "include/view/unit_details_view.php";