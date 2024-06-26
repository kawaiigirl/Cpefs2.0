<?php
include "include/base_includes.php";
include "include/authenticator.php";
include "include/view/layout.php";
include "include/functions_booking.php";

$currentBookings = GetBookings();
$pastBookings = GetBookings(true);
$displayCurrentBookings = CreateDisplayBookingArray($currentBookings);
require "include/view/my_bookings_view.php";