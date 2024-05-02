<?php
session_start();
const incPATH = "../include/";
include_once "../include/common.php";
include_once "../include/class_Booking.php";
include_once "redirect_to_adminlogin.php";
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=member_emails.csv");
global $dbc;
if(isset($_GET['status']) && $_GET['status'] == 0)
{
    $where = " AND status = 0 ";
}
else
{
    $where = "";
}
$qry="Select * from cpefs_members Where active = 1 $where order by member_name";
$res=$dbc->getResult($qry,__LINE__,__FILE__);
$comma = "";
while($row=$res->fetch_array(MYSQLI_ASSOC))
{
	//echo($comma.$row['member_name'].' <'.$row[member_email].'>');
	echo($comma.$row['member_email']);
	$comma = ",\r\n";
}