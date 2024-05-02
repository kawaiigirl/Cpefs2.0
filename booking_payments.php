<?php
include "include/base_includes.php";
include "include/authenticator.php";
include "include/view/layout.php";
global $dbc;

$qry="Select * from cpefs_payments Where booking_id =?";

$rows=$dbc->getNumRows($qry,__LINE__,__FILE__,array("i",& $_GET['id']));	/// setting pagination variables

$qry2="Select cpefs_booking.*,cpefs_members.member_name,cpefs_units.unit_name From cpefs_booking 					  
					Inner Join cpefs_members On cpefs_members.member_id=cpefs_booking.member_id
					Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id
					Where cpefs_booking.booking_id = ? ";

$row = $dbc->getSingleRow($qry2,__LINE__,__FILE__,array("i",& $_GET['id']));
include "include/view/booking_payments_view.php";