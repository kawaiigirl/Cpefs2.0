<?php 
include "include/admin_header.php";
include_once "../include/calendar.php";
include_once "redirect_to_adminlogin.php";
$_SESSION['backpage']="deleted_bookings.php";
global $dbc;
$f=0;
$op = 0;
$msg="";
if(isset($_GET['act']) && $_GET['act']=="undo_delete")
{
	$check = $dbc->getSingleRow("Select booking_id From cpefs_booking where booking_id = ?",__LINE__,__FILE__,array("i",& $_GET['id']));
	if ($check == null || $check['booking_id'] != $_GET['id'])
	{
		$dbc->insert("INSERT INTO cpefs_booking SELECT * FROM cpefs_deleted_booking WHERE booking_id = ?",__LINE__,__FILE__,array("i",& $_GET['id']));
		$check = $dbc->getSingleRow("Select booking_id From cpefs_booking where booking_id =?",__LINE__,__FILE__,array("i",& $_GET['id']));
		if ( $check['booking_id'] == $_GET['id'])
		{
		    $dbc->delete("Delete From cpefs_deleted_booking where booking_id = ?",__LINE__,__FILE__,array("i",& $_GET['id']));
	    	$msg="Booking Deletion Successfully undone";
		}
		
	}else
		$msg="Booking Deletion could not be undone";
}

if(isset($_POST['submit']))
{
	/* Multiple Deletion */
	if($_POST['delete'] !="")	
	{
		foreach($_POST['delete'] as $del)
		{
			$dbc->delete(" Delete from cpefs_deleted_booking where booking_id = ?",__LINE__,__FILE__,array("i",& $del));
		}
		$msg = "Booking deleted";
	}
	$op=2;
}
$sub = "";
$qrystringord = "";
$qryParamTypes = "";
$qryParams = array();
if(count($_POST)>0  || (isset($_GET['unit']) && $_GET['unit']!="") || (isset($_GET['mem']) && $_GET['mem']!="") || (isset($_GET['cdate']) && $_GET['cdate']!="") || (isset($_GET['status']) && $_GET['status']!=""))
{
	if(count($_POST)>0)
    {
        $_GET['page'] = 1;
        if(isset($_POST['unit']))
            $_GET['unit'] = $_POST['unit'];
        elseif(!isset($_GET['unit']))
            $_GET['unit'] = "";
        if(isset($_POST['mem']))
            $_GET['mem'] = $_POST['mem'];
        elseif(!isset($_GET['mem']))
            $_GET['mem'] = "";
        if(isset($_POST['status']))
            $_GET['status'] = $_POST['status'];
        elseif(!isset($_GET['status']))
            $_GET['status'] = "";
        if(isset($_POST['cdate']))
            $_GET['cdate'] = $_POST['cdate'];
        elseif(!isset($_GET['cdate']))
            $_GET['cdate'] = "";
    }
	if(isset($_GET['unit']) && $_GET['unit']!="")
	{
		$qrystringord="&unit=$_GET[unit]";
		$sub=" And cpefs_deleted_booking.unit_id=?";
        $qryParamTypes .="i";
        $qryParams[] = $_GET['unit'];
	}
	if(isset($_GET['mem']) && $_GET['mem']!="")
	{
		$qrystringord .="&mem=$_GET[mem]";
		$sub .=" And cpefs_members.member_name Like ?";
        $qryParamTypes .="s";
        $qryParams[] = "%" . $_GET['mem'] . "%";
	}
	if(isset($_GET['cdate']) && $_GET['cdate']!="")
	{
		$qrystringord .="&cdate=$_GET[cdate]";
		$sub .=" And check_in_date = ?";
        $qryParamTypes .="s";
        $qryParams[] = $_GET['cdate'];
	}
	if(isset($_GET['status']) && $_GET['status']!="")
	{
		$qrystringord .="&status=$_GET[status]";
		$sub .=" And approve = ?";
		$qryParamTypes .="s";
        $qryParams[] = $_GET['status'];
	}
}
$ord = "";
$ord_next_unit = 1;
$ord_next_mem = 3;
$ord_next_cdate = 5;
$img_name = "up";
if(isset($_GET["ord"])) {
    if ($_GET["ord"] == 1) {
        $ord = " cpefs_deleted_booking.unit_id DESC";
        $img_name = "down";
        $ord_next_unit = 2;
    } elseif ($_GET["ord"] == 2) {
        $ord = " cpefs_deleted_booking.unit_id ASC";
        $img_name = "up";
    } elseif ($_GET["ord"] == 3) {
        $ord = " member_name DESC";
        $img_name = "down";
        $ord_next_mem = 4;
    } elseif ($_GET["ord"] == 4) {
        $ord = " member_name ASC";
        $img_name = "up";
    } elseif ($_GET["ord"] == 5) {
        $ord = " check_in_date DESC";
        $img_name = "down";
        $ord_next_cdate = 6;
    } elseif ($_GET["ord"] == 6) {
        $ord = " check_in_date ASC";
        $img_name = "up";
    }
}
if($ord!="")
{
	$qstringall = $qrystringord. "&ord=$_GET[ord]";
	$ord = " Order By " . $ord;
}
else
{
	$qstringall = $qrystringord;
	$ord = " Order By booking_date";
}
$querystring = $qstringall;

/* Pagination variables */

if(isset($_GET['page']) && $_GET['page'] !='')
	$page=$_GET['page'];
else
	$page=1;

$qry="Select cpefs_deleted_booking.*,cpefs_members.member_name,cpefs_units.unit_name From cpefs_deleted_booking 					  
					Inner Join cpefs_members On cpefs_members.member_id=cpefs_deleted_booking.member_id
					Inner Join cpefs_units On cpefs_units.unit_id=cpefs_deleted_booking.unit_id
					Where 1 $sub $ord ";

$variables = getAdminPagingVariables($qry,$page,$qryParams,$qryParamTypes);

$toshow = 25; //overwriting admin settings;
$start = $variables['start'];
$totalPages = $variables['totalPages'];
$rows = $variables['rows'];
$qryParamsRefs = $variables['paramsRefs'];
/*************************/
$pageTitle = "Deleted Bookings";
$pageLink = "deleted_bookings.php";
include "display_bookings.php";
include "include/admin_footer.php";